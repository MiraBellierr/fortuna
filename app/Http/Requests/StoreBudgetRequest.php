<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id', function ($attribute, $value, $fail) {
                if (! Category::where('id', $value)->where('user_id', auth()->id())->exists()) {
                    $fail('The selected category is invalid or does not belong to you.');
                }
            }],
            'amount' => ['required', 'numeric', 'min:0'],
            'month' => ['required', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'], // YYYY-MM format
        ];
    }
}
