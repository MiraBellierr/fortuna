<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::where('user_id', Auth::id())
            ->with('category');

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        return response()->json($query->get());
    }

    public function store(StoreBudgetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        // Prevent duplicate budgets for the same category in the same month
        $existing = Budget::where('user_id', Auth::id())
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'budget' => ['A budget already exists for this category in the selected month.'],
            ]);
        }

        $budget = Budget::create($validated);

        return response()->json($budget, 201);
    }

    public function show(Budget $budget)
    {
        $this->authorizeBudget($budget);

        return response()->json($budget->load('category'));
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $this->authorizeBudget($budget);

        $validated = $request->validated();

        // If month or category is changing, check for duplicates
        if (isset($validated['month']) || isset($validated['category_id'])) {
            $month = $validated['month'] ?? $budget->month;
            $catId = $validated['category_id'] ?? $budget->category_id;

            $existing = Budget::where('user_id', Auth::id())
                ->where('category_id', $catId)
                ->where('month', $month)
                ->where('id', '!=', $budget->id)
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'budget' => ['A budget already exists for this category in the selected month.'],
                ]);
            }
        }

        $budget->update($validated);

        return response()->json($budget);
    }

    public function destroy(Budget $budget)
    {
        $this->authorizeBudget($budget);
        $budget->delete();

        return response()->json(null, 204);
    }

    private function authorizeBudget(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'budget' => ['You do not have permission to modify this budget.'],
            ]);
        }
    }

    private function validateCategoryOwnership(int $categoryId)
    {
        $category = Category::where('id', $categoryId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $category) {
            throw ValidationException::withMessages([
                'category_id' => ['The selected category is invalid or does not belong to you.'],
            ]);
        }
    }
}
