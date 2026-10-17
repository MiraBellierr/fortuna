<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlySummaryController extends Controller
{
    /**
     * Download the current month's summary as a PDF.
     */
    public function download(): Response
    {
        $user = Auth::user();
        $now = now();
        $currentMonth = $now->format('Y-m');

        $totals = Transaction::where('user_id', $user->id)
            ->whereYear('transaction_date', $now->year)
            ->whereMonth('transaction_date', $now->month)
            ->select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get();

        $income = (float) $totals->where('type', 'Income')->sum('total');
        $spend = (float) $totals->where('type', 'Expense')->sum('total');

        // Same spending subquery as the dashboard's budget check, kept in step with it.
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

        $budgeted = (float) $budgets->sum('amount');

        // Overshoots first, then the hardest-run lines, as on the dashboard.
        $budgets = $budgets->sortByDesc(function (Budget $budget): array {
            $limit = (float) $budget->amount;
            $spent = (float) $budget->spent;

            return [$spent > $limit ? 1 : 0, $limit > 0 ? $spent / $limit : ($spent > 0 ? INF : 0)];
        })->values();

        $topCategories = Transaction::where('user_id', $user->id)
            ->where('type', 'Expense')
            ->whereYear('transaction_date', $now->year)
            ->whereMonth('transaction_date', $now->month)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->get()
            ->map(fn ($row): array => [
                'name' => $row->category->name,
                'amount' => (float) $row->total,
                'share' => $spend > 0 ? round((float) $row->total / $spend * 100, 1) : 0.0,
            ]);

        $entryCount = Transaction::where('user_id', $user->id)
            ->whereYear('transaction_date', $now->year)
            ->whereMonth('transaction_date', $now->month)
            ->count();

        return Pdf::loadView('reports.monthly-summary', [
            'userName' => $user->name,
            'monthName' => $now->format('F Y'),
            'monthOnly' => $now->format('F'),
            'generatedAt' => $now,
            'entryCount' => $entryCount,
            'income' => $income,
            'spend' => $spend,
            'net' => $income - $spend,
            'budgetRemaining' => $budgeted > 0 ? $budgeted - $spend : null,
            'budgets' => $budgets,
            'topCategories' => $topCategories,
        ])->setPaper('a4')->download('fortuna-summary-'.$currentMonth.'.pdf');
    }
}
