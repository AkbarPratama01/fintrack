<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Get all categories (system + user's custom categories)
        $categories = Category::where(function($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');
        
        $incomeCategories = $categories->get('income', collect());
        $expenseCategories = $categories->get('expense', collect());
        
        // Count transactions per category
        $categoryStats = Category::where(function($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->withCount(['transactions' => function($query) {
                $query->where('user_id', Auth::id());
            }])
            ->get()
            ->keyBy('id');
        
        return view('categories.index', compact('incomeCategories', 'expenseCategories', 'categoryStats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['user_id'] = Auth::id();
        
        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        // Check if category belongs to user (can't edit system categories)
        if ($category->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only edit your own categories.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category belongs to user (can't delete system categories)
        if ($category->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only delete your own categories.');
        }

        // Check if category has transactions
        $transactionCount = $category->transactions()->where('user_id', Auth::id())->count();
        
        if ($transactionCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete category that has transactions.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
