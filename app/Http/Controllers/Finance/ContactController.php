<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::where('user_id', Auth::id())
            ->orderBy('name');

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf_cnpj', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(20);

        return view('finance.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('finance.contacts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Cliente,Fornecedor,Funcionário,Outro',
            'cpf_cnpj' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'zipcode' => 'nullable|string|max:10',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
        ]);

        $validated['user_id'] = Auth::id();

        Contact::create($validated);

        return redirect()->route('finance.contacts.index')
            ->with('success', 'Contato criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        Gate::authorize('view', $contact);
        return view('finance.contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        Gate::authorize('update', $contact);
        return redirect()->route('finance.contacts.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        Gate::authorize('update', $contact);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Cliente,Fornecedor,Funcionário,Outro',
            'cpf_cnpj' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'zipcode' => 'nullable|string|max:10',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
        ]);

        $contact->update($validated);

        return redirect()->route('finance.contacts.index')
            ->with('success', 'Contato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        Gate::authorize('delete', $contact);
        $contact->delete();
        
        return redirect()->route('finance.contacts.index')
            ->with('success', 'Contato removido com sucesso!');
    }
}
