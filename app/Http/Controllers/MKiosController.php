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
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->mkiosTransactions()
            ->with('wallet')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);
        
        // Statistics
        $totalTransactions = $user->mkiosTransactions()->completed()->count();
        $totalProfit = $user->mkiosTransactions()->completed()->sum('profit');
        $totalBalanceDeducted = $user->mkiosTransactions()->completed()->sum('balance_deducted');
        $totalCashReceived = $user->mkiosTransactions()->completed()->sum('cash_received');
        
        $wallets = $user->wallets;
        
        return view('m-kios.index', compact(
            'user',
            'transactions',
            'totalTransactions',
            'totalProfit',
            'totalBalanceDeducted',
            'totalCashReceived',
            'wallets'
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
            'customer_id' => 'nullable|string|max:100',
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

        if ($request->transaction_type === 'token_listrik' && empty($request->customer_id)) {
            return back()->withErrors(['customer_id' => 'ID Pelanggan wajib diisi untuk transaksi Token Listrik'])
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
