<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Payable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PayableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payable::where('user_id', Auth::id())
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

        $payables = $query->paginate(20);
        $accounts = Account::where('user_id', Auth::id())
            ->where('active', true)
            ->orderBy('name')
            ->get();
        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();
        $contacts = Contact::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        $summary = [
            'pending' => Payable::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->sum('amount'),
            'paid' => Payable::where('user_id', Auth::id())
                ->where('status', 'paid')
                ->sum('amount'),
            'overdue' => Payable::where('user_id', Auth::id())
                ->where('status', 'overdue')
                ->sum('amount'),
        ];

        return view('finance.payables.index', compact('payables', 'accounts', 'categories', 'contacts', 'summary'));
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

        Payable::create($validated);

        return redirect()->route('finance.payables.index')
            ->with('success', 'Conta a pagar criada com sucesso!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payable $payable)
    {
        Gate::authorize('update', $payable);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'type' => 'required|in:Pessoa Física (PF),Pessoa Jurídica (PJ)',
            'account_id' => 'nullable|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'paid_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payable->update($validated);

        return redirect()->route('finance.payables.index')
            ->with('success', 'Conta a pagar atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payable $payable)
    {
        Gate::authorize('delete', $payable);
        $payable->delete();
        
        return redirect()->route('finance.payables.index')
            ->with('success', 'Conta a pagar removida com sucesso!');
    }
}
