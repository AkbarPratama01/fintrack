<?php

namespace App\Http\Controllers;

use App\Models\MKiosTransaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MKiosController extends Controller
{
    /**
     * Display the M-KIOS page with transaction history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build query with filters
        $query = $user->mkiosTransactions()->with(['wallet', 'customer']);
        
        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by wallet
        if ($request->filled('wallet_id')) {
            $query->where('wallet_id', $request->wallet_id);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        
        // Filter by search (phone number, customer ID, or product code)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('phone_number', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(20)->withQueryString();
        
        // Statistics (based on filters or all)
        $statsQuery = $user->mkiosTransactions()->completed();
        
        // Apply same filters to stats
        if ($request->filled('transaction_type')) {
            $statsQuery->where('transaction_type', $request->transaction_type);
        }
        if ($request->filled('wallet_id')) {
            $statsQuery->where('wallet_id', $request->wallet_id);
        }
        if ($request->filled('start_date')) {
            $statsQuery->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $statsQuery->whereDate('transaction_date', '<=', $request->end_date);
        }
        
        $totalTransactions = $statsQuery->count();
        $totalProfit = $statsQuery->sum('profit');
        $totalBalanceDeducted = $statsQuery->sum('balance_deducted');
        $totalCashReceived = $statsQuery->sum('cash_received');
        
        $wallets = $user->wallets;
        $customers = $user->customers()->active()->get();
        
        // Data for charts
        // Transaction by Type
        $transactionsByType = $user->mkiosTransactions()
            ->selectRaw('transaction_type, COUNT(*) as count, SUM(profit) as total_profit')
            ->where('status', 'completed')
            ->groupBy('transaction_type')
            ->get();
        
        // Transaction by Status
        $transactionsByStatus = $user->mkiosTransactions()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Daily transactions for last 7 days
        $dailyTransactions = $user->mkiosTransactions()
            ->selectRaw('DATE(transaction_date) as date, COUNT(*) as count, SUM(profit) as profit')
            ->where('transaction_date', '>=', now()->subDays(6))
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Fill missing dates with zero
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $transaction = $dailyTransactions->firstWhere('date', $date);
            $dates->push([
                'date' => $date,
                'count' => $transaction ? $transaction->count : 0,
                'profit' => $transaction ? $transaction->profit : 0,
            ]);
        }
        
        return view('m-kios.index', compact(
            'user',
            'transactions',
            'totalTransactions',
            'totalProfit',
            'totalBalanceDeducted',
            'totalCashReceived',
            'wallets',
            'customers',
            'transactionsByType',
            'transactionsByStatus',
            'dailyTransactions',
            'dates'
        ));
    }

    /**
     * Store a new M-KIOS transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:pulsa,dana,gopay,token_listrik',
            'product_code' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',
            'pln_customer_id' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'balance_deducted' => 'required|numeric|min:0',
            'cash_received' => 'required|numeric|min:0',
            'provider' => 'nullable|string|max:50',
            'wallet_id' => 'required|exists:wallets,id',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'nullable|date',
        ]);

        $user = Auth::user();

        // Manual validation for conditional fields
        if (in_array($request->transaction_type, ['pulsa', 'dana', 'gopay']) && empty($request->phone_number)) {
            return back()->withErrors(['phone_number' => 'Nomor HP wajib diisi untuk transaksi ' . strtoupper($request->transaction_type)])
                ->withInput();
        }

        if ($request->transaction_type === 'token_listrik' && empty($request->pln_customer_id)) {
            return back()->withErrors(['pln_customer_id' => 'Nomor ID PLN wajib diisi untuk transaksi Token Listrik'])
                ->withInput();
        }
        
        // Verify wallet ownership
        $wallet = Wallet::where('id', $request->wallet_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Check if wallet has enough balance
        if ($wallet->balance < $request->balance_deducted) {
            return back()->withErrors(['wallet_id' => 'Saldo wallet tidak mencukupi!'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $user, $wallet) {
                // Calculate profit
                $profit = $request->cash_received - $request->balance_deducted;

                // Create M-KIOS transaction
                $mkiosTransaction = MKiosTransaction::create([
                    'user_id' => $user->id,
                    'transaction_type' => $request->transaction_type,
                    'product_code' => $request->product_code,
                    'phone_number' => $request->phone_number,
                    'pln_customer_id' => $request->pln_customer_id,
                    'customer_id' => $request->customer_id,
                    'balance_deducted' => $request->balance_deducted,
                    'cash_received' => $request->cash_received,
                    'profit' => $profit,
                    'provider' => $request->provider,
                    'wallet_id' => $request->wallet_id,
                    'notes' => $request->notes,
                    'status' => 'completed',
                    'transaction_date' => $request->transaction_date ?? now(),
                ]);

                // Deduct balance from wallet
                $wallet->subtractBalance((float) $request->balance_deducted);
            });

            return redirect()->route('m-kios.index')
                ->with('success', 'Transaksi M-KIOS berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(MKiosTransaction $mkiosTransaction)
    {
        // Authorization check
        if ($mkiosTransaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('m-kios.show', compact('mkiosTransaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(MKiosTransaction $mkiosTransaction)
    {
        // Authorization check
        if ($mkiosTransaction->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $wallets = $user->wallets;
        $customers = $user->customers()->active()->get();

        return view('m-kios.edit', compact('mkiosTransaction', 'wallets', 'customers'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, MKiosTransaction $mkiosTransaction)
    {
        // Authorization check
        if ($mkiosTransaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'transaction_type' => 'required|in:pulsa,dana,gopay,token_listrik',
            'product_code' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',
            'pln_customer_id' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'balance_deducted' => 'required|numeric|min:0',
            'cash_received' => 'required|numeric|min:0',
            'provider' => 'nullable|string|max:50',
            'wallet_id' => 'required|exists:wallets,id',
            'notes' => 'nullable|string|max:1000',
            'transaction_date' => 'nullable|date',
            'status' => 'required|in:completed,pending,failed',
        ]);

        $user = Auth::user();

        // Manual validation for conditional fields
        if (in_array($request->transaction_type, ['pulsa', 'dana', 'gopay']) && empty($request->phone_number)) {
            return back()->withErrors(['phone_number' => 'Nomor HP wajib diisi untuk transaksi ' . strtoupper($request->transaction_type)])
                ->withInput();
        }

        if ($request->transaction_type === 'token_listrik' && empty($request->pln_customer_id)) {
            return back()->withErrors(['pln_customer_id' => 'Nomor ID PLN wajib diisi untuk transaksi Token Listrik'])
                ->withInput();
        }
        
        // Verify wallet ownership
        $wallet = Wallet::where('id', $request->wallet_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            DB::transaction(function () use ($request, $mkiosTransaction, $wallet) {
                $oldBalanceDeducted = $mkiosTransaction->balance_deducted;
                $oldWalletId = $mkiosTransaction->wallet_id;
                $oldStatus = $mkiosTransaction->status;
                $newBalanceDeducted = $request->balance_deducted;
                $newStatus = $request->status;

                // Calculate profit
                $profit = $request->cash_received - $request->balance_deducted;

                // Handle wallet balance changes
                // Case 1: Status changed from completed to non-completed
                if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                    // Restore old balance to old wallet
                    if ($mkiosTransaction->wallet) {
                        $mkiosTransaction->wallet->addBalance((float) $oldBalanceDeducted);
                    }
                }
                // Case 2: Status changed from non-completed to completed
                elseif ($oldStatus !== 'completed' && $newStatus === 'completed') {
                    // Check if new wallet has enough balance
                    if ($wallet->balance < $newBalanceDeducted) {
                        throw new \Exception('Saldo wallet tidak mencukupi!');
                    }
                    // Deduct new balance from new wallet
                    $wallet->subtractBalance((float) $newBalanceDeducted);
                }
                // Case 3: Status remains completed but wallet or amount changed
                elseif ($oldStatus === 'completed' && $newStatus === 'completed') {
                    // If wallet changed
                    if ($oldWalletId !== $request->wallet_id) {
                        // Restore balance to old wallet
                        if ($mkiosTransaction->wallet) {
                            $mkiosTransaction->wallet->addBalance((float) $oldBalanceDeducted);
                        }
                        // Check if new wallet has enough balance
                        if ($wallet->balance < $newBalanceDeducted) {
                            throw new \Exception('Saldo wallet tidak mencukupi!');
                        }
                        // Deduct from new wallet
                        $wallet->subtractBalance((float) $newBalanceDeducted);
                    }
                    // If wallet same but amount changed
                    elseif ($oldBalanceDeducted != $newBalanceDeducted) {
                        $difference = $newBalanceDeducted - $oldBalanceDeducted;
                        if ($difference > 0) {
                            // Need to deduct more
                            if ($wallet->balance < $difference) {
                                throw new \Exception('Saldo wallet tidak mencukupi!');
                            }
                            $wallet->subtractBalance((float) $difference);
                        } else {
                            // Return some balance
                            $wallet->addBalance((float) abs($difference));
                        }
                    }
                }

                // Update transaction
                $mkiosTransaction->update([
                    'transaction_type' => $request->transaction_type,
                    'product_code' => $request->product_code,
                    'phone_number' => $request->phone_number,
                    'pln_customer_id' => $request->pln_customer_id,
                    'customer_id' => $request->customer_id,
                    'balance_deducted' => $request->balance_deducted,
                    'cash_received' => $request->cash_received,
                    'profit' => $profit,
                    'provider' => $request->provider,
                    'wallet_id' => $request->wallet_id,
                    'notes' => $request->notes,
                    'status' => $request->status,
                    'transaction_date' => $request->transaction_date ?? $mkiosTransaction->transaction_date,
                ]);
            });

            return redirect()->route('m-kios.index')
                ->with('success', 'Transaksi M-KIOS berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified transaction.
     */
    public function destroy(MKiosTransaction $mkiosTransaction)
    {
        // Authorization check
        if ($mkiosTransaction->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($mkiosTransaction) {
            // Restore balance to wallet if transaction was completed
            if ($mkiosTransaction->status === 'completed' && $mkiosTransaction->wallet) {
                $mkiosTransaction->wallet->addBalance((float) $mkiosTransaction->balance_deducted);
            }

            $mkiosTransaction->delete();
        });

        return redirect()->route('m-kios.index')
            ->with('success', 'Transaksi M-KIOS berhasil dihapus!');
    }
}