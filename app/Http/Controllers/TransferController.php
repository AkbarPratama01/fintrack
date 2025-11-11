<?php

namespace App\Http\Controllers;

use App\Models\WalletTransfer;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $transfers = Auth::user()->walletTransfers()
            ->with(['fromWallet', 'toWallet'])
            ->latest('transfer_date')
            ->latest('created_at')
            ->paginate(15);
        
        $wallets = Auth::user()->wallets;
        
        // Statistics
        $totalTransfers = Auth::user()->walletTransfers()->count();
        $totalAmount = Auth::user()->walletTransfers()->sum('amount');
        $thisMonthTransfers = Auth::user()->walletTransfers()
            ->whereMonth('transfer_date', now()->month)
            ->whereYear('transfer_date', now()->year)
            ->count();
        $thisMonthAmount = Auth::user()->walletTransfers()
            ->whereMonth('transfer_date', now()->month)
            ->whereYear('transfer_date', now()->year)
            ->sum('amount');
        
        return view('transfers.index', compact(
            'transfers',
            'wallets',
            'totalTransfers',
            'totalAmount',
            'thisMonthTransfers',
            'thisMonthAmount'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id|different:to_wallet_id',
            'to_wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ], [
            'from_wallet_id.different' => 'Source and destination wallets must be different.',
        ]);

        // Check if both wallets belong to user
        $fromWallet = Wallet::findOrFail($validated['from_wallet_id']);
        $toWallet = Wallet::findOrFail($validated['to_wallet_id']);
        
        if ($fromWallet->user_id !== Auth::id() || $toWallet->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if source wallet has sufficient balance
        if ($fromWallet->balance < $validated['amount']) {
            return redirect()->back()
                ->with('error', 'Insufficient balance in source wallet.')
                ->withInput();
        }

        DB::transaction(function () use ($validated, $fromWallet, $toWallet) {
            // Create transfer record
            $validated['user_id'] = Auth::id();
            WalletTransfer::create($validated);

            // Update wallet balances
            $fromWallet->subtractBalance((float)$validated['amount']);
            $toWallet->addBalance((float)$validated['amount']);
        });

        return redirect()->back()->with('success', 'Transfer completed successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletTransfer $transfer): RedirectResponse
    {
        // Check if transfer belongs to authenticated user
        if ($transfer->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($transfer) {
            $fromWallet = $transfer->fromWallet;
            $toWallet = $transfer->toWallet;

            // Revert the transfer
            $fromWallet->addBalance((float)$transfer->amount);
            $toWallet->subtractBalance((float)$transfer->amount);

            // Delete transfer
            $transfer->delete();
        });

        return redirect()->back()->with('success', 'Transfer deleted and balances reverted successfully!');
    }
}
