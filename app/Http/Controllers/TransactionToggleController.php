<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionToggleController extends Controller
{
    /**
     * Toggles the transaction type between 'Income' and 'Expense'.
     * When toggling, the category must also be updated to a category
     * of the same type belonging to the user.
     */
    public function toggle(Request $request, Transaction $transaction)
    {
        // Authorization check
        if ($transaction->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'transaction' => ['You do not have permission to modify this transaction.'],
            ]);
        }

        // Determine new type
        $newType = $transaction->type === 'Income' ? 'Expense' : 'Income';

        // Find a default category of the new type belonging to the user
        $category = Category::where('user_id', Auth::id())
            ->where('type', $newType)
            ->first();

        if (! $category) {
            throw ValidationException::withMessages([
                'category' => ["No categories of type {$newType} found. Please create one first."],
            ]);
        }

        // Update transaction
        $transaction->update([
            'type' => $newType,
            'category_id' => $category->id,
        ]);

        return response()->json([
            'message' => "Transaction toggled to {$newType}",
            'transaction' => $transaction->load('category'),
        ]);
    }
}
