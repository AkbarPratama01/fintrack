<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

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

    /**
     * Set budget for a category.
     */
    public function setBudget(Request $request, Category $category): RedirectResponse
    {
        // Check if category belongs to expense type
        if ($category->type !== 'expense') {
            return redirect()->back()->with('error', 'Budget can only be set for expense categories.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,yearly',
        ]);

        // Calculate start and end date based on period
        $startDate = $validated['period'] === 'monthly' 
            ? Carbon::now()->startOfMonth()
            : Carbon::now()->startOfYear();
        
        $endDate = $validated['period'] === 'monthly'
            ? Carbon::now()->endOfMonth()
            : Carbon::now()->endOfYear();

        // Check if budget already exists for this period
        $existingBudget = Budget::where('user_id', Auth::id())
            ->where('category_id', $category->id)
            ->where('period', $validated['period'])
            ->whereDate('start_date', $startDate)
            ->first();

        if ($existingBudget) {
            $existingBudget->update([
                'amount' => $validated['amount'],
                'end_date' => $endDate,
                'is_active' => true,
            ]);
        } else {
            Budget::create([
                'user_id' => Auth::id(),
                'category_id' => $category->id,
                'amount' => $validated['amount'],
                'period' => $validated['period'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Budget set successfully!');
    }

    /**
     * Update budget.
     */
    public function updateBudget(Request $request, Budget $budget): RedirectResponse
    {
        // Check if budget belongs to user
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:monthly,yearly',
        ]);

        $budget->update($validated);

        return redirect()->back()->with('success', 'Budget updated successfully!');
    }

    /**
     * Delete budget for a category.
     */
    public function deleteBudget(Budget $budget): RedirectResponse
    {
        // Check if budget belongs to user
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->delete();

        return redirect()->back()->with('success', 'Budget deleted successfully!');
    }

    /**
     * Get budget status for categories.
     */
    public function getBudgetStatus(): View
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->where('is_active', true)
            ->with('category')
            ->get();

        $budgetData = $budgets->map(function($budget) {
            return [
                'id' => $budget->id,
                'category' => $budget->category,
                'amount' => $budget->amount,
                'period' => $budget->period,
                'spending' => $budget->getSpending(),
                'remaining' => $budget->getRemaining(),
                'percentage' => $budget->getPercentage(),
                'status' => $budget->getStatus(),
                'is_exceeded' => $budget->isExceeded(),
            ];
        });

        // Get expense categories for budget modal
        $expenseCategories = Category::where(function($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', Auth::id());
            })
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('budgets.index', compact('budgetData', 'expenseCategories'));
    }
}
