<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportPageController extends Controller
{
    /**
     * The detailed monthly and yearly analysis for one ledger year.
     */
    public function index(Request $request): View
    {
        $userId = (int) Auth::id();

        $years = Transaction::ledgerYears($userId);
        $year = $this->resolveYear($request->query('year'));

        if (! $years->contains($year)) {
            $years = $years->prepend($year)->sortDesc()->values();
        }

        $months = $this->monthlyBreakdown($userId, $year);
        $totals = $this->yearTotals($months);
        $yearlyTotals = $this->yearlyTotals($userId);

        return view('reports.index', [
            'year' => $year,
            'years' => $years,
            'months' => $months,
            'totals' => $totals,
            'movement' => $this->movement($year, $totals, $yearlyTotals),
            'expenseCategories' => $this->categoryBreakdown($userId, $year, 'Expense'),
            'incomeCategories' => $this->categoryBreakdown($userId, $year, 'Income'),
            'yearlyTotals' => $yearlyTotals,
        ]);
    }

    /**
     * The requested year, or the current one when the query value is not a four-digit year.
     */
    private function resolveYear(mixed $query): int
    {
        if (is_string($query) && preg_match('/^\d{4}$/', $query)) {
            return (int) $query;
        }

        return (int) now()->format('Y');
    }

    /**
     * The twelve months of the year with their income, spending, and net; empty months included.
     *
     * @return array<int, array{number: int, name: string, short: string, start: string, end: string, entries: int, income: float, expense: float, net: float, savings_rate: float|null}>
     */
    private function monthlyBreakdown(int $userId, int $year): array
    {
        $rows = Transaction::where('user_id', $userId)
            ->whereYear('transaction_date', $year)
            ->select(
                DB::raw("strftime('%m', transaction_date) as month"),
                'type',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as entries')
            )
            ->groupBy('month', 'type')
            ->get();

        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);

            $months[$month] = [
                'number' => $month,
                'name' => $date->format('F'),
                'short' => $date->format('M'),
                'start' => $date->startOfMonth()->toDateString(),
                'end' => $date->endOfMonth()->toDateString(),
                'entries' => 0,
                'income' => 0.0,
                'expense' => 0.0,
                'net' => 0.0,
                'savings_rate' => null,
            ];
        }

        foreach ($rows as $row) {
            $month = (int) $row->month;

            if ($row->type === 'Income') {
                $months[$month]['income'] = (float) $row->total;
            } else {
                $months[$month]['expense'] = (float) $row->total;
            }

            $months[$month]['entries'] += (int) $row->entries;
        }

        foreach ($months as $month => $values) {
            $net = $values['income'] - $values['expense'];

            $months[$month]['net'] = $net;
            $months[$month]['savings_rate'] = $values['income'] > 0
                ? round($net / $values['income'] * 100, 1)
                : null;
        }

        return array_values($months);
    }

    /**
     * The year summed across its months.
     *
     * @param  array<int, array<string, mixed>>  $months
     * @return array{entries: int, income: float, expense: float, net: float, savings_rate: float|null}
     */
    private function yearTotals(array $months): array
    {
        $income = (float) array_sum(array_column($months, 'income'));
        $expense = (float) array_sum(array_column($months, 'expense'));
        $net = $income - $expense;

        return [
            'entries' => (int) array_sum(array_column($months, 'entries')),
            'income' => $income,
            'expense' => $expense,
            'net' => $net,
            'savings_rate' => $income > 0 ? round($net / $income * 100, 1) : null,
        ];
    }

    /**
     * Spending or income by category for the year, largest first, with each category's share.
     *
     * @return Collection<int, array{name: string, color: string, amount: float, share: float, entries: int}>
     */
    private function categoryBreakdown(int $userId, int $year, string $type): Collection
    {
        $rows = Transaction::where('user_id', $userId)
            ->where('type', $type)
            ->whereYear('transaction_date', $year)
            ->select('category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as entries'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category:id,name,color')
            ->get();

        $overall = (float) $rows->sum('total');

        return $rows->map(fn ($row): array => [
            'name' => $row->category->name,
            'color' => $row->category->color,
            'amount' => (float) $row->total,
            'share' => $overall > 0 ? round((float) $row->total / $overall * 100, 1) : 0.0,
            'entries' => (int) $row->entries,
        ]);
    }

    /**
     * Income, spending, and net per ledger year, newest first.
     *
     * @return Collection<int, array{year: int, entries: int, income: float, expense: float, net: float, savings_rate: float|null}>
     */
    private function yearlyTotals(int $userId): Collection
    {
        return Transaction::where('user_id', $userId)
            ->select(
                DB::raw("strftime('%Y', transaction_date) as year"),
                'type',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as entries')
            )
            ->groupBy('year', 'type')
            ->get()
            ->groupBy('year')
            ->sortKeysDesc()
            ->map(function (Collection $rows, string $year): array {
                $income = (float) $rows->where('type', 'Income')->sum('total');
                $expense = (float) $rows->where('type', 'Expense')->sum('total');
                $net = $income - $expense;

                return [
                    'year' => (int) $year,
                    'entries' => (int) $rows->sum('entries'),
                    'income' => $income,
                    'expense' => $expense,
                    'net' => $net,
                    'savings_rate' => $income > 0 ? round($net / $income * 100, 1) : null,
                ];
            })
            ->values();
    }

    /**
     * The selected year's movement against the closest earlier ledger year, if one exists.
     *
     * @param  array{entries: int, income: float, expense: float, net: float, savings_rate: float|null}  $totals
     * @param  Collection<int, array{year: int, entries: int, income: float, expense: float, net: float, savings_rate: float|null}>  $yearlyTotals
     * @return array{year: int, income: float|null, expense: float|null, net: float|null, savings_rate: float|null}|null
     */
    private function movement(int $year, array $totals, Collection $yearlyTotals): ?array
    {
        $prior = $yearlyTotals->first(fn (array $row): bool => $row['year'] < $year);

        if (! $prior) {
            return null;
        }

        $percent = fn (float $current, float $previous): ?float => $previous > 0
            ? round(($current - $previous) / $previous * 100, 1)
            : null;

        return [
            'year' => $prior['year'],
            'income' => $percent($totals['income'], $prior['income']),
            'expense' => $percent($totals['expense'], $prior['expense']),
            'net' => $percent($totals['net'], $prior['net']),
            'savings_rate' => $totals['savings_rate'] !== null && $prior['savings_rate'] !== null
                ? round($totals['savings_rate'] - $prior['savings_rate'], 1)
                : null,
        ];
    }
}
