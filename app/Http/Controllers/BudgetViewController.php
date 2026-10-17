<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BudgetViewController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->query('month', now()->format('Y-m'));

        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }

        // Get budgets for the selected month, with actual spending per category
        $spendingSub = '(select coalesce(sum(t.amount), 0) from transactions t'
            .' where t.category_id = budgets.category_id'
            ." and t.type = 'Expense'"
            ." and strftime('%Y-%m', t.transaction_date) = budgets.month"
            .' and t.user_id = budgets.user_id)';

        $budgets = Budget::where('user_id', Auth::id())
            ->where('month', $month)
            ->with('category')
            ->select('budgets.*')
            ->selectRaw($spendingSub.' as spent')
            ->get();

        // Overshoots first, then the hardest-run lines
        $budgets = $budgets->sortByDesc(function (Budget $budget): array {
            $limit = (float) $budget->amount;
            $spent = (float) $budget->spent;

            return [$spent > $limit ? 1 : 0, $limit > 0 ? $spent / $limit : ($spent > 0 ? INF : 0)];
        })->values();

        // Categories belonging to the authenticated user, for the "Add Budget" modal
        $categories = Auth::user()->categories()->orderBy('name')->get();

        // Budget being edited, for the "Set Budget" modal
        $editing = Budget::where('user_id', Auth::id())
            ->with('category')
            ->find(request('edit'));

        return view('budgets.index', [
            'budgets' => $budgets,
            'selectedMonth' => $month,
            'categories' => $categories,
            'editing' => $editing,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(app(StoreBudgetRequest::class)->rules());

        $existing = $this->findDuplicateBudget($validated['category_id'], $validated['month']);

        if ($existing) {
            return back()->withErrors(['budget' => __('A budget already exists for this category in the selected month.')]);
        }

        Budget::create($validated + ['user_id' => Auth::id()]);

        return redirect()->route('budgets.index', ['month' => $validated['month']])
            ->with('success', __('Budget was set for ":month".', ['month' => $validated['month']]));
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate(app(UpdateBudgetRequest::class)->rules());

        $month = $validated['month'] ?? $budget->month;
        $categoryId = $validated['category_id'] ?? $budget->category_id;

        $existing = Budget::where('user_id', Auth::id())
            ->where('category_id', $categoryId)
            ->where('month', $month)
            ->where('id', '!=', $budget->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['budget' => __('A budget already exists for this category in the selected month.')]);
        }

        $budget->update($validated);

        return redirect()->route('budgets.index', ['month' => $month])
            ->with('success', __('Budget for ":month" was updated.', ['month' => $month]));
    }

    private function findDuplicateBudget(int $categoryId, string $month): ?Budget
    {
        return Budget::where('user_id', Auth::id())
            ->where('category_id', $categoryId)
            ->where('month', $month)
            ->first();
    }
}
