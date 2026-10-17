<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\StoreTransactionWithReceiptRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Requests\UpdateTransactionWithReceiptRequest;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransactionViewController extends Controller
{
    public function index(Request $request): View
    {
        // The shared filter set, applied by Transaction::scopeFiltered (page, API, and CSV export agree).
        $filters = Transaction::normalizeFilters($request->query());

        $sort = in_array($request->query('sort'), ['oldest', 'highest', 'lowest'], true)
            ? $request->query('sort')
            : 'newest';

        ['column' => $sortColumn, 'direction' => $sortDirection] = Transaction::resolveSort($sort);

        $transactions = Transaction::where('user_id', Auth::id())
            ->with('category')
            ->filtered($filters)
            ->sorted($sortColumn, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        // Categories belonging to the authenticated user, for the filter and the add/edit sheets.
        $categories = Auth::user()->categories()->orderBy('name')->get();

        // Transaction being edited, for the "Edit Transaction" sheet.
        $editingId = $request->query('edit');
        $editing = is_scalar($editingId)
            ? Transaction::where('user_id', Auth::id())->find($editingId)
            : null;

        // The filters that actually apply — the form sends empty strings for "All" options and blank fields.
        $activeFilters = $filters;

        // "Newest first" is the default order, so only a chosen order counts as an active filter.
        if ($sort !== 'newest') {
            $activeFilters['sort'] = $sort;
        }

        // Anything inside the "More filters" disclosure so it can open itself with its count.
        $advancedFilters = array_intersect_key($activeFilters, array_flip(['start_date', 'end_date', 'amount_min', 'amount_max']));

        return view('transactions.index', [
            'transactions' => $transactions,
            'activeFilters' => $activeFilters,
            'advancedFiltersActive' => $advancedFilters !== [],
            'advancedCount' => count($advancedFilters),
            'sort' => $sort,
            'categories' => $categories,
            'editing' => $editing,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Reuse the same validation rules as the API, depending on whether a receipt file is attached
        $rules = app($request->hasFile('receipt') ? StoreTransactionWithReceiptRequest::class : StoreTransactionRequest::class)->rules();

        $validated = $request->validate($rules);
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $this->uploadReceipt($request->file('receipt'));
        }

        $transaction = Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', __('Transaction of :amount in :category was added.', [
                'amount' => 'MYR '.number_format((float) $transaction->amount, 2),
                'category' => $transaction->category->name,
            ]));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Reuse the same validation rules as the API, depending on whether a receipt file is attached
        $rules = app($request->hasFile('receipt') ? UpdateTransactionWithReceiptRequest::class : UpdateTransactionRequest::class)->rules();

        $validated = $request->validate($rules);

        if ($request->hasFile('receipt')) {
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $validated['receipt_path'] = $this->uploadReceipt($request->file('receipt'));
        }

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', __('Transaction was updated.'));
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->receipt_path) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', __('Transaction was deleted.'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        // Duplicate ids arrive when the mobile list and the table mirror the same selection.
        $ids = collect((array) $request->input('ids', []))
            ->filter(fn ($id) => is_numeric($id) && (int) $id > 0)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return redirect()->route('transactions.index')
                ->with('error', __('Select at least one transaction to delete.'));
        }

        $transactions = Transaction::where('user_id', Auth::id())
            ->whereIn('id', $ids)
            ->get();

        foreach ($transactions as $transaction) {
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }

            $transaction->delete();
        }

        return redirect()->route('transactions.index')
            ->with('success', trans_choice(
                '{0} No matching transactions were deleted.|{1} 1 transaction was deleted.|[2,*] :count transactions were deleted.',
                $transactions->count()
            ));
    }

    private function uploadReceipt(UploadedFile $file): string
    {
        return $file->storeAs(
            'receipts',
            'user_'.Auth::id().'_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension(),
            'public'
        );
    }
}
