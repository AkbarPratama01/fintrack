<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the report generation page.
     */
    public function index()
    {
        $user = Auth::user();
        $wallets = $user->wallets;
        
        return view('reports.index', compact('wallets'));
    }

    /**
     * Generate and display the report based on parameters.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:income-expense,category-breakdown,monthly-summary,wallet-balance,cash-flow',
            'period' => 'required|in:this-month,last-month,last-3-months,last-6-months,this-year,custom',
            'start_date' => 'required_if:period,custom|nullable|date',
            'end_date' => 'required_if:period,custom|nullable|date|after_or_equal:start_date',
            'wallets' => 'nullable|array',
            'wallets.*' => 'exists:wallets,id',
        ]);

        $user = Auth::user();
        
        // Determine date range based on period
        [$startDate, $endDate] = $this->getDateRange($request->period, $request->start_date, $request->end_date);
        
        // Get selected wallets or all user wallets
        $selectedWallets = $request->wallets 
            ? $user->wallets()->whereIn('id', $request->wallets)->get()
            : $user->wallets;

        // Generate report data based on type
        $reportData = match($request->report_type) {
            'income-expense' => $this->generateIncomeExpenseReport($user, $startDate, $endDate, $selectedWallets),
            'category-breakdown' => $this->generateCategoryBreakdownReport($user, $startDate, $endDate, $selectedWallets),
            'monthly-summary' => $this->generateMonthlySummaryReport($user, $startDate, $endDate, $selectedWallets),
            'wallet-balance' => $this->generateWalletBalanceReport($user, $startDate, $endDate, $selectedWallets),
            'cash-flow' => $this->generateCashFlowReport($user, $startDate, $endDate, $selectedWallets),
        };

        return view('reports.show', [
            'reportType' => $request->report_type,
            'period' => $request->period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'wallets' => $selectedWallets,
            'reportData' => $reportData,
        ]);
    }

    /**
     * Get date range based on period selection.
     */
    private function getDateRange($period, $customStart = null, $customEnd = null)
    {
        return match($period) {
            'this-month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last-month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'last-3-months' => [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()],
            'last-6-months' => [now()->subMonths(6)->startOfMonth(), now()->endOfMonth()],
            'this-year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [Carbon::parse($customStart), Carbon::parse($customEnd)],
        };
    }

    /**
     * Generate Income vs Expense report.
     */
    private function generateIncomeExpenseReport($user, $startDate, $endDate, $wallets)
    {
        $walletIds = $wallets->pluck('id');

        $income = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->sum('amount');

        $expense = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->sum('amount');

        $netIncome = $income - $expense;
        $savingsRate = $income > 0 ? ($netIncome / $income) * 100 : 0;

        // Get daily breakdown for chart
        $dailyData = $user->transactions()
            ->select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return [
            'totalIncome' => $income,
            'totalExpense' => $expense,
            'netIncome' => $netIncome,
            'savingsRate' => $savingsRate,
            'dailyData' => $dailyData,
            'transactionCount' => $user->transactions()
                ->whereBetween('date', [$startDate, $endDate])
                ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
                ->count(),
        ];
    }

    /**
     * Generate Category Breakdown report.
     */
    private function generateCategoryBreakdownReport($user, $startDate, $endDate, $wallets)
    {
        $walletIds = $wallets->pluck('id');

        $incomeByCategory = $user->transactions()
            ->select('category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        $expenseByCategory = $user->transactions()
            ->select('category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        $totalIncome = $incomeByCategory->sum('total');
        $totalExpense = $expenseByCategory->sum('total');

        return [
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
        ];
    }

    /**
     * Generate Monthly Summary report.
     */
    private function generateMonthlySummaryReport($user, $startDate, $endDate, $wallets)
    {
        $walletIds = $wallets->pluck('id');

        $monthlyData = $user->transactions()
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->net = $item->income - $item->expense;
                $item->savings_rate = $item->income > 0 ? ($item->net / $item->income) * 100 : 0;
                return $item;
            });

        $averageIncome = $monthlyData->avg('income');
        $averageExpense = $monthlyData->avg('expense');
        $totalMonths = $monthlyData->count();

        return [
            'monthlyData' => $monthlyData,
            'averageIncome' => $averageIncome,
            'averageExpense' => $averageExpense,
            'totalMonths' => $totalMonths,
        ];
    }

    /**
     * Generate Wallet Balance report.
     */
    private function generateWalletBalanceReport($user, $startDate, $endDate, $wallets)
    {
        $walletsData = $wallets->map(function ($wallet) use ($startDate, $endDate) {
            $transactions = $wallet->transactions()
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();

            $incomeTotal = $transactions->where('type', 'income')->sum('amount');
            $expenseTotal = $transactions->where('type', 'expense')->sum('amount');

            return [
                'wallet' => $wallet,
                'currentBalance' => $wallet->balance,
                'incomeTotal' => $incomeTotal,
                'expenseTotal' => $expenseTotal,
                'transactionCount' => $transactions->count(),
            ];
        });

        $totalBalance = $walletsData->sum('currentBalance');
        $totalIncome = $walletsData->sum('incomeTotal');
        $totalExpense = $walletsData->sum('expenseTotal');

        return [
            'wallets' => $walletsData,
            'totalBalance' => $totalBalance,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
        ];
    }

    /**
     * Generate Cash Flow report.
     */
    private function generateCashFlowReport($user, $startDate, $endDate, $wallets)
    {
        $walletIds = $wallets->pluck('id');

        // Get opening balance (balance before start date)
        $openingBalance = $wallets->sum('balance');
        
        // Calculate transactions before start date to get true opening balance
        $transactionsBeforeStart = $user->transactions()
            ->where('date', '<', $startDate)
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->select(
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->first();

        $openingBalance = $openingBalance - ($transactionsBeforeStart->total_income ?? 0) + ($transactionsBeforeStart->total_expense ?? 0);

        // Get period transactions
        $periodIncome = $user->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->sum('amount');

        $periodExpense = $user->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->sum('amount');

        $closingBalance = $openingBalance + $periodIncome - $periodExpense;
        $netCashFlow = $periodIncome - $periodExpense;

        // Daily cash flow for chart
        $dailyCashFlow = $user->transactions()
            ->select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as inflow'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as outflow')
            )
            ->whereBetween('date', [$startDate, $endDate])
            ->when($walletIds->isNotEmpty(), fn($q) => $q->whereIn('wallet_id', $walletIds))
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($item) {
                $item->net_flow = $item->inflow - $item->outflow;
                return $item;
            });

        return [
            'openingBalance' => $openingBalance,
            'periodIncome' => $periodIncome,
            'periodExpense' => $periodExpense,
            'closingBalance' => $closingBalance,
            'netCashFlow' => $netCashFlow,
            'dailyCashFlow' => $dailyCashFlow,
        ];
    }

    /**
     * Export report to PDF.
     */
    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF export using package like dompdf or snappy
        return back()->with('info', 'PDF export feature coming soon!');
    }

    /**
     * Export report to Excel.
     */
    public function exportExcel(Request $request)
    {
        // TODO: Implement Excel export using package like maatwebsite/excel
        return back()->with('info', 'Excel export feature coming soon!');
    }

    /**
     * Export report to CSV.
     */
    public function exportCsv(Request $request)
    {
        // TODO: Implement CSV export
        return back()->with('info', 'CSV export feature coming soon!');
    }
}
