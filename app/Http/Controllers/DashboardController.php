<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $now = now();
        $currentMonth = $now->format('Y-m');

        // Lifetime balance: all income minus all expenses
        $balanceData = Transaction::where('user_id', $user->id)
            ->select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get();

        $totalIncome = (float) $balanceData->where('type', 'Income')->sum('total');
        $totalExpense = (float) $balanceData->where('type', 'Expense')->sum('total');

        // Everything earned this calendar month
        $monthlyIncome = (float) Transaction::where('user_id', $user->id)
            ->where('type', 'Income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        // Everything recorded as spent this calendar month
        $monthlySpend = (float) Transaction::where('user_id', $user->id)
            ->where('type', 'Expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        // This month's budgets with actual spending per category
        $spendingSub = '(select coalesce(sum(t.amount), 0) from transactions t'
            .' where t.category_id = budgets.category_id'
            ." and t.type = 'Expense'"
            ." and strftime('%Y-%m', t.transaction_date) = budgets.month"
            .' and t.user_id = budgets.user_id)';

        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->with('category')
            ->select('budgets.*')
            ->selectRaw($spendingSub.' as spent')
            ->get();

        $totalBudgeted = (float) $budgets->sum('amount');

        // Overshoots first, then the hardest-run lines
        $budgets = $budgets->sortByDesc(function (Budget $budget): array {
            $limit = (float) $budget->amount;
            $spent = (float) $budget->spent;

            return [$spent > $limit ? 1 : 0, $limit > 0 ? $spent / $limit : ($spent > 0 ? INF : 0)];
        })->values();

        return view('dashboard', [
            'totalBalance' => $totalIncome - $totalExpense,
            'monthlyIncome' => $monthlyIncome,
            'monthlySpend' => $monthlySpend,
            'budgetRemaining' => $totalBudgeted > 0 ? $totalBudgeted - $monthlySpend : null,
            'budgets' => $budgets,
            'monthName' => $now->format('F Y'),
            'monthOnly' => $now->format('F'),
            'years' => Transaction::ledgerYears((int) Auth::id()),
        ]);
    }
}
