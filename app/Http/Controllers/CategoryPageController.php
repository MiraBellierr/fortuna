<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryPageController extends Controller
{
    public function index(): View
    {
        $categories = Auth::user()->categories()
            ->withCount(['transactions', 'budgets'])
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $editing = $categories->firstWhere('id', (int) request('edit'));

        return view('categories.index', [
            'categories' => $categories,
            'editing' => $editing,
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = Auth::user()->categories()->create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', __('Category ":name" was created.', ['name' => $category->name]));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', __('Category ":name" was updated.', ['name' => $category->name]));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->transactions()->exists() || $category->budgets()->exists()) {
            return back()
                ->with('error', __('Category ":name" is still used by transactions or budgets and cannot be deleted.', ['name' => $category->name]));
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', __('Category ":name" was deleted.', ['name' => $category->name]));
    }
}
