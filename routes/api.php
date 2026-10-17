<?php

use App\Http\Controllers\BudgetAlertController;
use App\Http\Controllers\BudgetAnalyticsController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryColorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardStatsController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionToggleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('categories/colors', [CategoryColorController::class, 'index'])->name('api.categories.colors');
    Route::patch('categories/{category}/color', [CategoryColorController::class, 'updateColor'])->name('api.categories.update-color');
    Route::apiResource('categories', CategoryController::class)->names([
        'index' => 'api.categories.index',
        'store' => 'api.categories.store',
        'show' => 'api.categories.show',
        'update' => 'api.categories.update',
        'destroy' => 'api.categories.destroy',
    ]);
    Route::patch('transactions/{transaction}/toggle-type', [TransactionToggleController::class, 'toggle'])->name('api.transactions.toggle-type');
    Route::apiResource('transactions', TransactionController::class)->names([
        'index' => 'api.transactions.index',
        'store' => 'api.transactions.store',
        'show' => 'api.transactions.show',
        'update' => 'api.transactions.update',
        'destroy' => 'api.transactions.destroy',
    ]);
    Route::get('receipts/{filename}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::apiResource('budgets', BudgetController::class)->names([
        'index' => 'api.budgets.index',
        'store' => 'api.budgets.store',
        'show' => 'api.budgets.show',
        'update' => 'api.budgets.update',
        'destroy' => 'api.budgets.destroy',
    ]);
    Route::get('budgets/analytics/actual-vs-budget', [BudgetAnalyticsController::class, 'getActualVsBudget']);
    Route::get('budgets/alerts/overshoots', [BudgetAlertController::class, 'getOvershoots']);
    Route::get('budgets/alerts/check-transaction', [BudgetAlertController::class, 'checkLastTransaction']);
    Route::get('reports/monthly', [FinancialReportController::class, 'getMonthlyAggregation']);
    Route::get('reports/summary', [FinancialReportController::class, 'getTotalSummary']);
    Route::get('reports/category-distribution', [FinancialReportController::class, 'getCategorySpendingDistribution']);
    Route::get('dashboard/stats', [DashboardStatsController::class, 'getQuickStats']);
});
