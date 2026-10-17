<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionExportController extends Controller
{
    /**
     * Stream the user's transactions as a CSV, mirroring the index's filters and ordering.
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = Transaction::normalizeFilters($request->query());

        $sort = in_array($request->query('sort'), ['oldest', 'highest', 'lowest'], true)
            ? $request->query('sort')
            : 'newest';

        ['column' => $sortColumn, 'direction' => $sortDirection] = Transaction::resolveSort($sort);

        $transactions = Transaction::where('user_id', Auth::id())
            ->with('category')
            ->filtered($filters)
            ->sorted($sortColumn, $sortDirection)
            ->lazy();

        return response()->streamDownload(function () use ($transactions) {
            $out = fopen('php://output', 'w');

            // UTF-8 BOM so spreadsheet apps read accented text correctly.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['Date', 'Type', 'Category', 'Description', 'Amount (MYR)'], ',', '"', '');

            foreach ($transactions as $transaction) {
                fputcsv($out, [
                    $transaction->transaction_date->format('Y-m-d'),
                    $transaction->type,
                    $transaction->category->name,
                    $transaction->description,
                    number_format((float) $transaction->amount, 2, '.', ''),
                ], ',', '"', '');
            }

            fclose($out);
        }, 'fortuna-transactions-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
