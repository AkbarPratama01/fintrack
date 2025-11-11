<?php

namespace App\Console\Commands;

use App\Models\ScheduledTransfer;
use App\Models\WalletTransfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessScheduledTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfers:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and execute scheduled transfers that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing scheduled transfers...');

        $transfers = ScheduledTransfer::where('status', 'active')
            ->whereDate('next_execution_date', '<=', now())
            ->with(['fromWallet', 'toWallet', 'user'])
            ->get();

        if ($transfers->isEmpty()) {
            $this->info('No scheduled transfers to process.');
            return Command::SUCCESS;
        }

        $processed = 0;
        $failed = 0;

        foreach ($transfers as $transfer) {
            if (!$transfer->shouldExecute()) {
                continue;
            }

            try {
                DB::beginTransaction();

                $fromWallet = $transfer->fromWallet;
                $toWallet = $transfer->toWallet;

                // Check sufficient balance
                if ($fromWallet->balance < $transfer->amount) {
                    $this->warn("Insufficient balance for transfer #{$transfer->id}");
                    Log::warning("Scheduled transfer #{$transfer->id} failed: Insufficient balance");
                    $failed++;
                    DB::rollBack();
                    continue;
                }

                // Deduct from source wallet
                $fromWallet->balance -= $transfer->amount;
                $fromWallet->save();

                // Add to destination wallet
                $toWallet->balance += $transfer->amount;
                $toWallet->save();

                // Record transfer
                WalletTransfer::create([
                    'user_id' => $transfer->user_id,
                    'from_wallet_id' => $fromWallet->id,
                    'to_wallet_id' => $toWallet->id,
                    'amount' => $transfer->amount,
                    'description' => 'Transfer Terjadwal (Auto): ' . ($transfer->description ?? 'Otomatis'),
                    'transfer_date' => now(),
                ]);

                // Update scheduled transfer
                $transfer->execution_count++;
                $transfer->last_executed_at = now();
                $transfer->calculateNextExecutionDate();
                
                // Check if should complete
                if ($transfer->end_date && $transfer->next_execution_date->gt($transfer->end_date)) {
                    $transfer->status = 'completed';
                    $this->info("Transfer #{$transfer->id} completed (reached end date)");
                }
                
                $transfer->save();

                DB::commit();
                
                $processed++;
                $this->info("Processed transfer #{$transfer->id}: {$transfer->fromWallet->name} -> {$transfer->toWallet->name} ({$transfer->amount})");
                
                Log::info("Scheduled transfer #{$transfer->id} executed successfully", [
                    'from' => $fromWallet->name,
                    'to' => $toWallet->name,
                    'amount' => $transfer->amount,
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                $this->error("Failed to process transfer #{$transfer->id}: {$e->getMessage()}");
                Log::error("Scheduled transfer #{$transfer->id} failed", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        $this->info("Finished processing scheduled transfers.");
        $this->info("Processed: {$processed}, Failed: {$failed}");

        return Command::SUCCESS;
    }
}
