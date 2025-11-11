<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\MKiosTransaction;
use App\Models\Wallet;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30'); // Default 30 days
        
        // Date range based on period
        $startDate = Carbon::now()->subDays($period);
        $endDate = Carbon::now();
        
        // Total Wallets Balance
        $totalBalance = $user->wallets()->sum('balance');
        $wallets = $user->wallets()->get();
        
        // Income & Expense from Transactions
        $income = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
            
        $expense = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        
        // M-KIOS Profit
        $mkiosProfit = $user->mkiosTransactions()
            ->where('status', 'completed')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('profit');
        
        // Net Income (Income + M-KIOS Profit - Expense)
        $netIncome = $income + $mkiosProfit - $expense;
        
        // Transaction Count
        $transactionCount = $user->transactions()
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
            
        $mkiosTransactionCount = $user->mkiosTransactions()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->count();
        
        // Income vs Expense by Category
        $incomeByCategory = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(fn($items) => $items->sum('amount'));
            
        $expenseByCategory = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(fn($items) => $items->sum('amount'));
        
        // Daily Cash Flow (Last 30 days)
        $dailyCashFlow = collect();
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            $dayIncome = $user->transactions()
                ->where('type', 'income')
                ->whereDate('date', $date)
                ->sum('amount');
                
            $dayExpense = $user->transactions()
                ->where('type', 'expense')
                ->whereDate('date', $date)
                ->sum('amount');
                
            $dayMkiosProfit = $user->mkiosTransactions()
                ->where('status', 'completed')
                ->whereDate('transaction_date', $date)
                ->sum('profit');
            
            $dailyCashFlow->push([
                'date' => $date,
                'income' => $dayIncome + $dayMkiosProfit,
                'expense' => $dayExpense,
                'net' => ($dayIncome + $dayMkiosProfit) - $dayExpense
            ]);
        }
        
        // Recent Transactions (Last 10)
        $recentTransactions = $user->transactions()
            ->with(['category', 'wallet'])
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();
        
        // Recent M-KIOS Transactions (Last 5)
        $recentMKiosTransactions = $user->mkiosTransactions()
            ->with('wallet')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();
        
        // Top Expense Categories
        $topExpenseCategories = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function($items) {
                return [
                    'category' => $items->first()->category,
                    'total' => $items->sum('amount'),
                    'count' => $items->count()
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();
        
        // Wallet Performance
        $walletStats = $user->wallets()->get()->map(function($wallet) use ($startDate, $endDate) {
            $walletIncome = $wallet->transactions()
                ->where('type', 'income')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
                
            $walletExpense = $wallet->transactions()
                ->where('type', 'expense')
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');
                
            $walletMkiosProfit = $wallet->mkiosTransactions()
                ->where('status', 'completed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('profit');
            
            return [
                'wallet' => $wallet,
                'income' => $walletIncome + $walletMkiosProfit,
                'expense' => $walletExpense,
                'net' => ($walletIncome + $walletMkiosProfit) - $walletExpense
            ];
        });
        
        return view('dashboard', compact(
            'totalBalance',
            'wallets',
            'income',
            'expense',
            'mkiosProfit',
            'netIncome',
            'transactionCount',
            'mkiosTransactionCount',
            'incomeByCategory',
            'expenseByCategory',
            'dailyCashFlow',
            'recentTransactions',
            'recentMKiosTransactions',
            'topExpenseCategories',
            'walletStats',
            'period'
        ));
    }
}
