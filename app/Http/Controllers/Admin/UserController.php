<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserCredentialsMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where(function($q) {
                      $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                  })
                  ->latest();
        }, 'subscriptions.plan'])->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:user,admin,super_admin',
            'cpf_cnpj' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'send_credentials' => 'boolean',
        ]);

        $password = $request->filled('password') 
            ? $request->password 
            : \Illuminate\Support\Str::random(12);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => $validated['role'],
            'cpf_cnpj' => $validated['cpf_cnpj'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        if ($request->boolean('send_credentials', true)) {
            Mail::to($user->email)->send(new UserCredentialsMail($user, $password));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso! ' . ($request->boolean('send_credentials', true) ? 'Credenciais enviadas por email.' : ''));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,super_admin',
            'cpf_cnpj' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'Não é possível excluir o último super administrador.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
