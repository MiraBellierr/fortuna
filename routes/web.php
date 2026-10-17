<?php

use App\Http\Controllers\BudgetViewController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportPageController;
use App\Http\Controllers\TransactionExportController;
use App\Http\Controllers\TransactionViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [TransactionViewController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [TransactionExportController::class, 'export'])->name('transactions.export');
    Route::delete('/transactions/bulk', [TransactionViewController::class, 'bulkDestroy'])->name('transactions.bulk-destroy');
    Route::post('/transactions', [TransactionViewController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{transaction}', [TransactionViewController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionViewController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/budgets', [BudgetViewController::class, 'index'])->name('budgets.index');
    Route::post('/budgets', [BudgetViewController::class, 'store'])->name('budgets.store');
    Route::put('/budgets/{budget}', [BudgetViewController::class, 'update'])->name('budgets.update');
    Route::get('/categories', [CategoryPageController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryPageController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryPageController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryPageController::class, 'destroy'])->name('categories.destroy');
    Route::get('/reports', [ReportPageController::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly-summary', [MonthlySummaryController::class, 'download'])->name('reports.monthly-summary');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
