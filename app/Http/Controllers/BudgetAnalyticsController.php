<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetAnalyticsController extends Controller
{
    /**
     * Calculate "Actual vs Budget" for all expense categories in a given month.
     */
    public function getActualVsBudget(Request $request)
    {
        $user = Auth::user();
        $month = $request->query('month', now()->format('Y-m'));

        // 1. Get all budgets for the user for this month
        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $month)
            ->with('category')
            ->get();

        // 2. Calculate actual spending per category for this month
        // We only care about 'Expense' type transactions
        $actuals = Transaction::where('user_id', $user->id)
            ->where('type', 'Expense')
            ->whereMonth('transaction_date', date('m', strtotime($month.'-01')))
            ->whereYear('transaction_date', date('Y', strtotime($month.'-01')))
            ->select('category_id', DB::raw('SUM(amount) as total_spent'))
            ->groupBy('category_id')
            ->get()
            ->pluck('total_spent', 'category_id');

        // 3. Merge data to calculate variance
        $report = $budgets->map(function ($budget) use ($actuals) {
            $actual = $actuals->get($budget->category_id, 0);
            $budgetAmount = $budget->amount;
            $variance = $budgetAmount - $actual;
            $percentage = $budgetAmount > 0
                ? ($actual / $budgetAmount) * 100
                : ($actual > 0 ? 100 : 0);

            return [
                'category' => [
                    'id' => $budget->category->id,
                    'name' => $budget->category->name,
                    'color' => $budget->category->color,
                ],
                'budgeted' => $budgetAmount,
                'actual' => $actual,
                'variance' => $variance,
                'percentage' => round($percentage, 2),
                'status' => $variance >= 0 ? 'under_budget' : 'over_budget',
            ];
        });

        // 4. Calculate overall totals
        $totalBudgeted = $budgets->sum('amount');
        $totalActual = $actuals->sum();

        return response()->json([
            'month' => $month,
            'categories' => $report,
            'totals' => [
                'total_budgeted' => $totalBudgeted,
                'total_actual' => $totalActual,
                'total_variance' => $totalBudgeted - $totalActual,
                'overall_percentage' => $totalBudgeted > 0
                    ? round(($totalActual / $totalBudgeted) * 100, 2)
                    : ($totalActual > 0 ? 100 : 0),
            ],
        ]);
    }
}
