<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MKiosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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
    
    // Scheduled Transfer routes
    Route::resource('scheduled-transfers', \App\Http\Controllers\ScheduledTransferController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/scheduled-transfers/{scheduledTransfer}/toggle-status', [\App\Http\Controllers\ScheduledTransferController::class, 'toggleStatus'])->name('scheduled-transfers.toggle-status');
    Route::post('/scheduled-transfers/{scheduledTransfer}/execute', [\App\Http\Controllers\ScheduledTransferController::class, 'execute'])->name('scheduled-transfers.execute');
});

require __DIR__.'/auth.php';