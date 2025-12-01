<?php

namespace App\Console\Commands;

use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetMonthlyBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgets:reset-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset monthly budgets at the start of each month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Get all active monthly budgets
        $monthlyBudgets = Budget::where('is_active', true)
            ->where('period', 'monthly')
            ->get();

        $resetCount = 0;

        foreach ($monthlyBudgets as $budget) {
            // Check if budget start_date is from previous month
            $budgetMonth = Carbon::parse($budget->start_date);
            
            if ($budgetMonth->month !== $now->month || $budgetMonth->year !== $now->year) {
                // Update to current month
                $budget->update([
                    'start_date' => $now->copy()->startOfMonth(),
                    'end_date' => $now->copy()->endOfMonth(),
                ]);
                
                $resetCount++;
                
                $this->info("Reset budget for category {$budget->category->name} (ID: {$budget->id})");
            }
        }

        // Also handle yearly budgets at start of year
        if ($now->month === 1) {
            $yearlyBudgets = Budget::where('is_active', true)
                ->where('period', 'yearly')
                ->get();

            foreach ($yearlyBudgets as $budget) {
                $budgetYear = Carbon::parse($budget->start_date);
                
                if ($budgetYear->year !== $now->year) {
                    $budget->update([
                        'start_date' => $now->copy()->startOfYear(),
                        'end_date' => $now->copy()->endOfYear(),
                    ]);
                    
                    $resetCount++;
                    
                    $this->info("Reset yearly budget for category {$budget->category->name} (ID: {$budget->id})");
                }
            }
        }

        if ($resetCount > 0) {
            $this->info("Successfully reset {$resetCount} budget(s).");
        } else {
            $this->info("No budgets need to be reset at this time.");
        }

        return Command::SUCCESS;
    }
}