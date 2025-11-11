<?php

use App\Http\Controllers\MKiosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $wallets = $user->wallets;
    
    // Get categories
    $incomeCategories = \App\Models\Category::where('type', 'income')
        ->where(function($query) {
            $query->whereNull('user_id')
                  ->orWhere('user_id', Auth::id());
        })->get();
    $expenseCategories = \App\Models\Category::where('type', 'expense')
        ->where(function($query) {
            $query->whereNull('user_id')
                  ->orWhere('user_id', Auth::id());
        })->get();
    
    // Calculate statistics
    $totalBalance = $wallets->sum('balance');
    
    // Get current month transactions
    $currentMonth = now()->startOfMonth();
    $monthlyIncome = $user->transactions()
        ->where('type', 'income')
        ->where('date', '>=', $currentMonth)
        ->sum('amount');
    
    $monthlyExpense = $user->transactions()
        ->where('type', 'expense')
        ->where('date', '>=', $currentMonth)
        ->sum('amount');
    
    // Get today's transactions count
    $todayTransactions = $user->transactions()
        ->whereDate('date', today())
        ->count();
    
    // Get recent transactions (last 5)
    $recentTransactions = $user->transactions()
        ->with(['category', 'wallet'])
        ->orderBy('date', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Get expense breakdown by category (this month)
    $categoryBreakdown = $user->transactions()
        ->where('type', 'expense')
        ->where('date', '>=', $currentMonth)
        ->selectRaw('category_id, SUM(amount) as total')
        ->groupBy('category_id')
        ->with('category')
        ->get();
    
    $totalMonthlyExpense = $categoryBreakdown->sum('total');
    
    return view('dashboard', compact(
        'wallets', 
        'incomeCategories', 
        'expenseCategories',
        'totalBalance',
        'monthlyIncome',
        'monthlyExpense',
        'todayTransactions',
        'recentTransactions',
        'categoryBreakdown',
        'totalMonthlyExpense'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Wallet routes
    Route::resource('wallets', WalletController::class);
    Route::get('/api/wallets', [WalletController::class, 'getWallets'])->name('wallets.api');
    Route::patch('/wallets/{wallet}/add-balance', [WalletController::class, 'addBalance'])->name('wallets.add-balance');
    Route::patch('/wallets/{wallet}/subtract-balance', [WalletController::class, 'subtractBalance'])->name('wallets.subtract-balance');
    
    // Transaction routes
    Route::resource('transactions', TransactionController::class);
    Route::get('/api/transactions', [TransactionController::class, 'getTransactions'])->name('transactions.api');
    
    // Category routes
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    
    // Report routes
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [\App\Http\Controllers\ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/export/pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::post('/reports/export/excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::post('/reports/export/csv', [\App\Http\Controllers\ReportController::class, 'exportCsv'])->name('reports.export.csv');
    
    // M-KIOS routes
    Route::get('/m-kios', [MKiosController::class, 'index'])->name('m-kios.index');
    Route::post('/m-kios', [MKiosController::class, 'store'])->name('m-kios.store');
    Route::get('/m-kios/{mkiosTransaction}', [MKiosController::class, 'show'])->name('m-kios.show');
    Route::delete('/m-kios/{mkiosTransaction}', [MKiosController::class, 'destroy'])->name('m-kios.destroy');
    
    // Transfer routes
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
    Route::delete('/transfers/{walletTransfer}', [TransferController::class, 'destroy'])->name('transfers.destroy');
});

require __DIR__.'/auth.php';