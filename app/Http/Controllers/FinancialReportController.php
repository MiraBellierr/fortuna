<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    /**
     * Get aggregate totals for income and expenses by month/year.
     * Optimized for Line Charts showing spending trends.
     */
    public function getMonthlyAggregation(Request $request)
    {
        $user = Auth::user();
        $year = $request->query('year', date('Y'));

        $data = Transaction::where('user_id', $user->id)
            ->select(
                DB::raw("STRFTIME('%m', transaction_date) as month"),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('transaction_date', $year)
            ->groupBy('month', 'type')
            ->get();

        $report = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthKey = str_pad($m, 2, '0', STR_PAD_LEFT);
            $report[$monthKey] = [
                'Income' => 0,
                'Expense' => 0,
                'Net' => 0,
            ];
        }

        foreach ($data as $row) {
            $month = $row->month;
            $type = $row->type;
            $total = (float) $row->total;

            if (isset($report[$month])) {
                $report[$month][$type] = $total;
            }
        }

        $labels = [];
        $incomeSeries = [];
        $expenseSeries = [];
        $netSeries = [];

        $monthNames = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
            '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
            '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec',
        ];

        foreach ($report as $month => $values) {
            $labels[] = $monthNames[$month];
            $income = $values['Income'];
            $expense = $values['Expense'];

            $incomeSeries[] = $income;
            $expenseSeries[] = $expense;
            $netSeries[] = $income - $expense;
        }

        return response()->json([
            'year' => $year,
            'chart_data' => [
                'labels' => $labels,
                'series' => [
                    ['name' => 'Income', 'data' => $incomeSeries],
                    ['name' => 'Expense', 'data' => $expenseSeries],
                    ['name' => 'Net Profit', 'data' => $netSeries],
                ],
            ],
            'raw_data' => $report,
        ]);
    }

    /**
     * Get total aggregate totals for a specific time range.
     */
    public function getTotalSummary(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date', date('Y-m-d'));

        $results = Transaction::where('user_id', $user->id)
            ->select('type', DB::raw('SUM(amount) as total'))
            ->when($startDate, fn ($q) => $q->where('transaction_date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('transaction_date', '<=', $endDate))
            ->groupBy('type')
            ->get();

        $summary = [
            'Income' => 0,
            'Expense' => 0,
            'Net' => 0,
        ];

        foreach ($results as $row) {
            $summary[$row->type] = (float) $row->total;
        }

        $summary['Net'] = $summary['Income'] - $summary['Expense'];

        return response()->json($summary);
    }

    /**
     * Calculate spending percentages per category.
     * This is primarily used for Pie Charts.
     */
    public function getCategorySpendingDistribution(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date', date('Y-m-d'));

        $categoryTotals = Transaction::where('user_id', $user->id)
            ->where('type', 'Expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->when($startDate, fn ($q) => $q->where('transaction_date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->where('transaction_date', '<=', $endDate))
            ->groupBy('category_id')
            ->with('category:id,name,color')
            ->get();

        $overallTotal = $categoryTotals->sum('total');

        if ($overallTotal == 0) {
            return response()->json([
                'total_spent' => 0,
                'distribution' => [],
                'chart_data' => [
                    'series' => [],
                    'labels' => [],
                ],
            ]);
        }

        $distribution = $categoryTotals->map(function ($item) use ($overallTotal) {
            return [
                'category' => $item->category->name,
                'color' => $item->category->color,
                'amount' => (float) $item->total,
                'percentage' => round(($item->total / $overallTotal) * 100, 2),
            ];
        })->values();

        $chartSeries = $distribution->pluck('amount')->toArray();
        $chartLabels = $distribution->pluck('category')->toArray();
        $chartColors = $distribution->pluck('color')->toArray();

        return response()->json([
            'total_spent' => (float) $overallTotal,
            'distribution' => $distribution,
            'chart_data' => [
                'series' => $chartSeries,
                'labels' => $chartLabels,
                'colors' => $chartColors,
            ],
        ]);
    }
}
