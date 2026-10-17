<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryColorController extends Controller
{
    /**
     * Provide a set of curated colors for the color picker.
     */
    public function index()
    {
        $curatedColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#C9CBCF', '#4BC0C0', '#F7B66C', '#B5EAD7',
            '#E2B1F3', '#A8DADC', '#457B9D', '#1D3557', '#E63946',
        ];

        return response()->json([
            'colors' => $curatedColors,
        ]);
    }

    /**
     * Quickly update only the color of a category.
     */
    public function updateColor(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'color' => ['required', 'string', 'max:7'],
        ]);

        $category->update([
            'color' => $request->color,
        ]);

        return response()->json([
            'message' => 'Category color updated successfully',
            'category' => $category,
        ]);
    }
}
