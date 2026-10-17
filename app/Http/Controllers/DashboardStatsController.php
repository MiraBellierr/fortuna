<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    /**
     * Get the high-level quick stats for the dashboard.
     */
    public function getQuickStats(Request $request)
    {
        $user = Auth::user();
        $now = now();
        $currentMonth = $now->format('Y-m');

        // 1. Total Balance (Lifetime)
        // Sum of all Income - Sum of all Expenses
        $balanceData = Transaction::where('user_id', $user->id)
            ->select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get();

        $totalIncome = $balanceData->where('type', 'Income')->sum('total');
        $totalExpense = $balanceData->where('type', 'Expense')->sum('total');
        $totalBalance = $totalIncome - $totalExpense;

        // 2. Monthly Spend (Current Month)
        $monthlySpend = Transaction::where('user_id', $user->id)
            ->where('type', 'Expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        // 3. Budget Remaining (Current Month)
        // Total of all budgets for this month - Actual spending for this month
        $totalBudgeted = Budget::where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->sum('amount');

        // Monthly spend is already calculated above
        $budgetRemaining = $totalBudgeted - $monthlySpend;

        return response()->json([
            'total_balance' => [
                'value' => round($totalBalance, 2),
                'label' => 'Total Balance',
                'trend' => $totalBalance >= 0 ? 'positive' : 'negative',
            ],
            'monthly_spend' => [
                'value' => round($monthlySpend, 2),
                'label' => 'Monthly Spend',
                'trend' => 'neutral',
            ],
            'budget_remaining' => [
                'value' => round($budgetRemaining, 2),
                'label' => 'Budget Remaining',
                'trend' => $budgetRemaining >= 0 ? 'positive' : 'negative',
            ],
        ]);
    }
}
