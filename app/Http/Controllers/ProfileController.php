<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where(function($q) {
                      $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                  })
                  ->latest();
        }, 'subscriptions.plan']);
        
        $activeSubscription = $user->subscriptions->first();
        
        return view('profile.index', compact('user', 'activeSubscription'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Senha atual incorreta']);
            }
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
}
