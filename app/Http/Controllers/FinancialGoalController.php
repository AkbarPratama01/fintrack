<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use App\Models\GoalContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->financialGoals()->with('contributions');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $goals = $query->latest()->paginate(12);
        
        // Statistics
        $totalGoals = $user->financialGoals()->count();
        $activeGoals = $user->financialGoals()->active()->count();
        $completedGoals = $user->financialGoals()->completed()->count();
        $totalTargetAmount = $user->financialGoals()->active()->sum('target_amount');
        $totalCurrentAmount = $user->financialGoals()->active()->sum('current_amount');
        $totalProgress = $totalTargetAmount > 0 ? ($totalCurrentAmount / $totalTargetAmount) * 100 : 0;
        
        return view('financial-goals.index', compact(
            'goals', 
            'totalGoals', 
            'activeGoals', 
            'completedGoals',
            'totalTargetAmount',
            'totalCurrentAmount',
            'totalProgress'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial-goals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'target_date' => 'required|date|after:today',
            'category' => 'nullable|string|in:savings,investment,debt_payment,purchase,emergency_fund,other',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'monthly_target' => 'nullable|numeric|min:0',
            'auto_save' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['current_amount'] = $validated['current_amount'] ?? 0;
        $validated['auto_save'] = $request->has('auto_save');
        $validated['status'] = 'active';
        
        FinancialGoal::create($validated);
        
        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialGoal $financialGoal)
    {
        if ($financialGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        $financialGoal->load(['contributions' => function($query) {
            $query->latest('contribution_date');
        }]);
        
        // Monthly contributions chart data
        $monthlyContributions = $financialGoal->contributions()
            ->selectRaw('DATE_FORMAT(contribution_date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('financial-goals.show', compact('financialGoal', 'monthlyContributions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialGoal $financialGoal)
    {
        if ($financialGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('financial-goals.edit', compact('financialGoal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialGoal $financialGoal)
    {
        if ($financialGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date',
            'category' => 'nullable|string|in:savings,investment,debt_payment,purchase,emergency_fund,other',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'monthly_target' => 'nullable|numeric|min:0',
            'auto_save' => 'boolean',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $validated['auto_save'] = $request->has('auto_save');
        
        $financialGoal->update($validated);
        
        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialGoal $financialGoal)
    {
        if ($financialGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        $financialGoal->delete();
        
        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal deleted successfully!');
    }
    
    /**
     * Add contribution to a goal.
     */
    public function addContribution(Request $request, FinancialGoal $financialGoal)
    {
        if ($financialGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'contribution_date' => 'required|date',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        DB::transaction(function () use ($financialGoal, $validated) {
            // Create contribution
            GoalContribution::create([
                'financial_goal_id' => $financialGoal->id,
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'contribution_date' => $validated['contribution_date'],
                'source' => $validated['source'] ?? 'manual',
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Update goal current amount
            $financialGoal->increment('current_amount', $validated['amount']);
            
            // Check if goal is completed
            if ($financialGoal->current_amount >= $financialGoal->target_amount) {
                $financialGoal->update(['status' => 'completed']);
            }
        });
        
        return back()->with('success', 'Contribution added successfully!');
    }
    
    /**
     * Withdraw from a goal.
     */
    public function withdraw(Request $request, FinancialGoal $financialGoal)
    {
        if ($financialGoal->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $financialGoal->current_amount,
            'contribution_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        DB::transaction(function () use ($financialGoal, $validated) {
            // Create negative contribution (withdrawal)
            GoalContribution::create([
                'financial_goal_id' => $financialGoal->id,
                'user_id' => Auth::id(),
                'amount' => -$validated['amount'],
                'contribution_date' => $validated['contribution_date'],
                'source' => 'withdrawal',
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Update goal current amount
            $financialGoal->decrement('current_amount', $validated['amount']);
            
            // Update status if no longer completed
            if ($financialGoal->status === 'completed' && $financialGoal->current_amount < $financialGoal->target_amount) {
                $financialGoal->update(['status' => 'active']);
            }
        });
        
        return back()->with('success', 'Withdrawal processed successfully!');
    }
}
