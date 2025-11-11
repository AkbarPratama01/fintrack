<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $transactions = Auth::user()->transactions()
            ->with(['wallet', 'category'])
            ->latest('date')
            ->paginate(15);
        
        $wallets = Auth::user()->wallets;
        
        $incomeCategories = Category::where(function($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->where('type', 'income')
            ->get();
            
        $expenseCategories = Category::where(function($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->where('type', 'expense')
            ->get();
        
        // Statistics
        $totalIncome = Auth::user()->transactions()
            ->where('type', 'income')
            ->sum('amount');
            
        $totalExpense = Auth::user()->transactions()
            ->where('type', 'expense')
            ->sum('amount');
            
        $monthlyIncome = Auth::user()->transactions()
            ->where('type', 'income')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
            
        $monthlyExpense = Auth::user()->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
        
        return view('transactions.index', compact(
            'transactions',
            'wallets',
            'incomeCategories',
            'expenseCategories',
            'totalIncome',
            'totalExpense',
            'monthlyIncome',
            'monthlyExpense'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $wallets = Auth::user()->wallets;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->get()
            ->groupBy('type');
        
        return view('transactions.create', compact('wallets', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Check if wallet belongs to user
        $wallet = Wallet::findOrFail($validated['wallet_id']);
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($validated, $wallet) {
            // Create transaction
            $transaction = Auth::user()->transactions()->create($validated);

            // Update wallet balance
            if ($transaction->type === 'income') {
                $wallet->addBalance((float)$transaction->amount);
            } else {
                $wallet->subtractBalance((float)$transaction->amount);
            }
        });

        return redirect()->back()->with('success', 'Transaction created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): View
    {
        // Check if transaction belongs to authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        // Check if transaction belongs to authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $wallets = Auth::user()->wallets;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->get()
            ->groupBy('type');
        
        return view('transactions.edit', compact('transaction', 'wallets', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        // Check if transaction belongs to authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Check if wallet belongs to user
        $wallet = Wallet::findOrFail($validated['wallet_id']);
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($validated, $transaction, $wallet) {
            $oldWallet = $transaction->wallet;
            $oldAmount = $transaction->amount;
            $oldType = $transaction->type;

            // Revert old transaction effect on old wallet
            if ($oldType === 'income') {
                $oldWallet->subtractBalance((float)$oldAmount);
            } else {
                $oldWallet->addBalance((float)$oldAmount);
            }

            // Update transaction
            $transaction->update($validated);

            // Apply new transaction effect on new wallet
            if ($transaction->type === 'income') {
                $wallet->addBalance((float)$transaction->amount);
            } else {
                $wallet->subtractBalance((float)$transaction->amount);
            }
        });

        return redirect()->back()->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        // Check if transaction belongs to authenticated user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($transaction) {
            $wallet = $transaction->wallet;

            // Revert transaction effect on wallet
            if ($transaction->type === 'income') {
                $wallet->subtractBalance((float)$transaction->amount);
            } else {
                $wallet->addBalance((float)$transaction->amount);
            }

            // Delete transaction
            $transaction->delete();
        });

        return redirect()->back()->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Get transactions as JSON for AJAX requests.
     */
    public function getTransactions(Request $request)
    {
        $query = Auth::user()->transactions()->with(['wallet', 'category']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        $transactions = $query->latest('date')->get();
        
        return response()->json($transactions);
    }
}
