<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionWithReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'exists:categories,id', function ($attribute, $value, $fail) {
                if (! Category::where('id', $value)->where('user_id', auth()->id())->exists()) {
                    $fail('The selected category is invalid or does not belong to you.');
                }
            }],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0.01'],
            'type' => ['sometimes', 'required', 'string', 'in:Income,Expense'],
            'transaction_date' => ['sometimes', 'required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'], // 2MB max
        ];
    }
}
