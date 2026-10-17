<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetAlertController extends Controller
{
    /**
     * Get a list of categories that have exceeded their budget for the current month.
     */
    public function getOvershoots(Request $request)
    {
        $user = Auth::user();
        $month = $request->query('month', now()->format('Y-m'));

        // We want categories where Actual Spending > Budgeted Amount
        // Use a join to find only those that have exceeded their budget
        $overshoots = DB::table('budgets')
            ->join('categories', 'budgets.category_id', '=', 'categories.id')
            ->join('transactions', function ($join) {
                $join->on('transactions.category_id', '=', 'budgets.category_id')
                    ->where('transactions.type', '=', 'Expense');
            })
            ->where('budgets.user_id', $user->id)
            ->where('budgets.month', $month)
            ->select(
                'categories.name',
                'categories.color',
                'budgets.amount as budgeted',
                DB::raw('SUM(transactions.amount) as actual')
            )
            ->groupBy('budgets.id', 'categories.name', 'categories.color', 'budgets.amount')
            ->havingRaw('SUM(transactions.amount) > budgets.amount')
            ->get();

        return response()->json([
            'month' => $month,
            'overshoots' => $overshoots,
            'count' => $overshoots->count(),
        ]);
    }

    /**
     * Check if the most recent transaction caused a budget overshoot.
     */
    public function checkLastTransaction(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        if (! $transactionId) {
            return response()->json(['error' => 'transaction_id is required'], 400);
        }

        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // We only care about expenses
        if ($transaction->type !== 'Expense') {
            return response()->json(['overshot' => false]);
        }

        $month = $transaction->transaction_date->format('Y-m');
        $budget = Budget::where('user_id', Auth::id())
            ->where('category_id', $transaction->category_id)
            ->where('month', $month)
            ->first();

        if (! $budget) {
            return response()->json(['overshot' => false, 'message' => 'No budget set for this category']);
        }

        $actual = Transaction::where('user_id', Auth::id())
            ->where('category_id', $transaction->category_id)
            ->where('type', 'Expense')
            ->whereMonth('transaction_date', date('m', strtotime($month.'-01')))
            ->whereYear('transaction_date', date('Y', strtotime($month.'-01')))
            ->sum('amount');

        return response()->json([
            'overshot' => $actual > $budget->amount,
            'actual' => $actual,
            'budget' => $budget->amount,
            'amount_over' => max(0, $actual - $budget->amount),
        ]);
    }
}
