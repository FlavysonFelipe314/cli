<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Receivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Receivable::where('user_id', Auth::id())
            ->with(['account', 'category', 'contact'])
            ->orderBy('due_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('account_id') && $request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $receivables = $query->paginate(20);
        $accounts = Account::where('user_id', Auth::id())
            ->where('active', true)
            ->orderBy('name')
            ->get();
        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'revenue')
            ->orderBy('name')
            ->get();
        $contacts = Contact::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        $summary = [
            'pending' => Receivable::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->sum('amount'),
            'received' => Receivable::where('user_id', Auth::id())
                ->where('status', 'received')
                ->sum('amount'),
            'overdue' => Receivable::where('user_id', Auth::id())
                ->where('status', 'overdue')
                ->sum('amount'),
        ];

        return view('finance.receivables.index', compact('receivables', 'accounts', 'categories', 'contacts', 'summary'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'type' => 'required|in:Pessoa Física (PF),Pessoa Jurídica (PJ)',
            'account_id' => 'nullable|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Receivable::create($validated);

        return redirect()->route('finance.receivables.index')
            ->with('success', 'Conta a receber criada com sucesso!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receivable $receivable)
    {
        Gate::authorize('update', $receivable);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'type' => 'required|in:Pessoa Física (PF),Pessoa Jurídica (PJ)',
            'account_id' => 'nullable|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'status' => 'required|in:pending,received,overdue,cancelled',
            'received_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $receivable->update($validated);

        return redirect()->route('finance.receivables.index')
            ->with('success', 'Conta a receber atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receivable $receivable)
    {
        Gate::authorize('delete', $receivable);
        $receivable->delete();
        
        return redirect()->route('finance.receivables.index')
            ->with('success', 'Conta a receber removida com sucesso!');
    }
}
