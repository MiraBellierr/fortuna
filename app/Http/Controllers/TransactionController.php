<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\StoreTransactionWithReceiptRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Requests\UpdateTransactionWithReceiptRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('category')
            ->filtered(Transaction::normalizeFilters($request->query()))
            ->sorted($request->query('sort_by'), $request->query('sort_order'))
            ->get();

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        // Determine which request to use based on whether a receipt is present
        $requestClass = $request->hasFile('receipt')
            ? StoreTransactionWithReceiptRequest::class
            : StoreTransactionRequest::class;

        $request->setContainer(app())->setRouteResolver(function () {
            return route();
        });

        $validated = $this->validateRequest($request, $requestClass);
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $this->uploadReceipt($request->file('receipt'));
        }

        $transaction = Transaction::create($validated);

        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        return response()->json($transaction->load('category'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $requestClass = $request->hasFile('receipt')
            ? UpdateTransactionWithReceiptRequest::class
            : UpdateTransactionRequest::class;

        $validated = $this->validateRequest($request, $requestClass);

        if ($request->hasFile('receipt')) {
            // Delete old receipt if it exists
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $validated['receipt_path'] = $this->uploadReceipt($request->file('receipt'));
        }

        $transaction->update($validated);

        return response()->json($transaction->load('category'));
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        // Delete receipt file from storage
        if ($transaction->receipt_path) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        return response()->json(null, 204);
    }

    private function validateRequest(Request $request, string $requestClass)
    {
        $validator = app($requestClass);
        // Use the rules from the FormRequest class
        $rules = $validator->rules();

        return $request->validate($rules);
    }

    private function uploadReceipt($file)
    {
        // Store in 'receipts' folder in the 'public' disk
        // Filename: user_{id}_{timestamp}.extension
        $filename = 'receipts/user_'.Auth::id().'_'.time().'.'.$file->getClientOriginalExtension();

        return $file->storeAs('receipts', 'user_'.Auth::id().'_'.time().'.'.$file->getClientOriginalExtension(), 'public');
    }

    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'transaction' => ['You do not have permission to access this transaction.'],
            ]);
        }
    }
}
