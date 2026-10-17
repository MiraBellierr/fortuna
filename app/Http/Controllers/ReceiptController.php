<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    /**
     * Securely stream a receipt file to the authenticated user.
     */
    public function show(Request $request, $filename)
    {
        // Extract transaction ID from request or find transaction by filename
        // Since filenames contain user_id, we can verify ownership.

        // Better approach: Find the transaction associated with this receipt
        $transaction = Transaction::where('receipt_path', 'receipts/'.$filename)
            ->where('user_id', Auth::id())
            ->first();

        if (! $transaction) {
            abort(403, 'You do not have permission to access this receipt.');
        }

        if (! Storage::disk('public')->exists('receipts/'.$filename)) {
            abort(404, 'Receipt file not found.');
        }

        // Return the file as a stream to avoid exposing the direct public URL
        return Storage::disk('public')->response('receipts/'.$filename);
    }
}
