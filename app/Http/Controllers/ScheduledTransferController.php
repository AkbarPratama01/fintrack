<?php

namespace App\Http\Controllers;

use App\Models\ScheduledTransfer;
use App\Models\Wallet;
use App\Models\WalletTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduledTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scheduledTransfers = Auth::user()->scheduledTransfers()
            ->with(['fromWallet', 'toWallet'])
            ->latest()
            ->get();
        
        $wallets = Auth::user()->wallets;
        
        return view('scheduled-transfers.index', compact('scheduledTransfers', 'wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id' => 'required|exists:wallets,id|different:from_wallet_id',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Verify wallets belong to user
        $fromWallet = Auth::user()->wallets()->findOrFail($validated['from_wallet_id']);
        $toWallet = Auth::user()->wallets()->findOrFail($validated['to_wallet_id']);

        // Check if from_wallet and to_wallet have same currency
        if ($fromWallet->currency !== $toWallet->currency) {
            return redirect()->back()
                ->with('error', 'Transfer terjadwal hanya bisa dilakukan antar wallet dengan mata uang yang sama!');
        }

        $validated['user_id'] = Auth::id();
        $validated['next_execution_date'] = $validated['start_date'];
        $validated['status'] = 'active';

        ScheduledTransfer::create($validated);

        return redirect()->back()->with('success', 'Transfer terjadwal berhasil dibuat!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScheduledTransfer $scheduledTransfer)
    {
        // Verify ownership
        if ($scheduledTransfer->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id' => 'required|exists:wallets,id|different:from_wallet_id',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,paused,completed,cancelled',
        ]);

        // Verify wallets belong to user
        $fromWallet = Auth::user()->wallets()->findOrFail($validated['from_wallet_id']);
        $toWallet = Auth::user()->wallets()->findOrFail($validated['to_wallet_id']);

        // Check currency match
        if ($fromWallet->currency !== $toWallet->currency) {
            return redirect()->back()
                ->with('error', 'Transfer terjadwal hanya bisa dilakukan antar wallet dengan mata uang yang sama!');
        }

        $scheduledTransfer->update($validated);

        return redirect()->back()->with('success', 'Transfer terjadwal berhasil diperbarui!');
    }

    /**
     * Toggle status (pause/resume)
     */
    public function toggleStatus(ScheduledTransfer $scheduledTransfer)
    {
        if ($scheduledTransfer->user_id !== Auth::id()) {
            abort(403);
        }

        $newStatus = $scheduledTransfer->status === 'active' ? 'paused' : 'active';
        $scheduledTransfer->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Transfer terjadwal diaktifkan kembali!' : 'Transfer terjadwal dijeda!';
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduledTransfer $scheduledTransfer)
    {
        if ($scheduledTransfer->user_id !== Auth::id()) {
            abort(403);
        }

        $scheduledTransfer->delete();

        return redirect()->back()->with('success', 'Transfer terjadwal berhasil dihapus!');
    }

    /**
     * Execute scheduled transfer manually
     */
    public function execute(ScheduledTransfer $scheduledTransfer)
    {
        if ($scheduledTransfer->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $fromWallet = $scheduledTransfer->fromWallet;
            $toWallet = $scheduledTransfer->toWallet;

            // Check sufficient balance
            if ($fromWallet->balance < $scheduledTransfer->amount) {
                return redirect()->back()
                    ->with('error', 'Saldo wallet tidak mencukupi untuk transfer!');
            }

            // Deduct from source wallet
            $fromWallet->balance -= $scheduledTransfer->amount;
            $fromWallet->save();

            // Add to destination wallet
            $toWallet->balance += $scheduledTransfer->amount;
            $toWallet->save();

            // Record transfer
            WalletTransfer::create([
                'user_id' => Auth::id(),
                'from_wallet_id' => $fromWallet->id,
                'to_wallet_id' => $toWallet->id,
                'amount' => $scheduledTransfer->amount,
                'description' => 'Transfer Terjadwal: ' . ($scheduledTransfer->description ?? 'Otomatis'),
                'transfer_date' => now(),
            ]);

            // Update scheduled transfer
            $scheduledTransfer->execution_count++;
            $scheduledTransfer->last_executed_at = now();
            $scheduledTransfer->calculateNextExecutionDate();
            
            // Check if should complete
            if ($scheduledTransfer->end_date && $scheduledTransfer->next_execution_date->gt($scheduledTransfer->end_date)) {
                $scheduledTransfer->status = 'completed';
            }
            
            $scheduledTransfer->save();

            DB::commit();

            return redirect()->back()->with('success', 'Transfer berhasil dieksekusi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
