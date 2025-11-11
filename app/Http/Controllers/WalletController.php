<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $wallets = Auth::user()->wallets()->latest()->get();
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('wallets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Auth::user()->wallets()->create($validated);

        return redirect()->back()->with('success', 'Wallet created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet): View
    {
        // Check if wallet belongs to authenticated user
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }
        
        $transactions = $wallet->transactions()->latest()->paginate(10);
        
        return view('wallets.show', compact('wallet', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet): View
    {
        // Check if wallet belongs to authenticated user
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('wallets.edit', compact('wallet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet): RedirectResponse
    {
        // Check if wallet belongs to authenticated user
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $wallet->update($validated);

        return redirect()->back()->with('success', 'Wallet updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet): RedirectResponse
    {
        // Check if wallet belongs to authenticated user
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }
        
        $wallet->delete();

        return redirect()->back()->with('success', 'Wallet deleted successfully!');
    }

    /**
     * Get all wallets as JSON for AJAX requests.
     */
    public function getWallets()
    {
        $wallets = Auth::user()->wallets()->get();
        return response()->json($wallets);
    }

    /**
     * Add balance to wallet.
     */
    public function addBalance(Request $request, Wallet $wallet): RedirectResponse
    {
        // Check if wallet belongs to authenticated user
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $wallet->addBalance((float) $request->amount);

        return redirect()->route('wallets.index')
            ->with('success', 'Saldo berhasil ditambahkan ke wallet ' . $wallet->name . '!');
    }

    /**
     * Subtract balance from wallet.
     */
    public function subtractBalance(Request $request, Wallet $wallet): RedirectResponse
    {
        // Check if wallet belongs to authenticated user
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Check if wallet has enough balance
        if ($wallet->balance < $request->amount) {
            return redirect()->route('wallets.index')
                ->with('error', 'Saldo wallet tidak mencukupi!');
        }

        $wallet->subtractBalance((float) $request->amount);

        return redirect()->route('wallets.index')
            ->with('success', 'Saldo berhasil dikurangi dari wallet ' . $wallet->name . '!');
    }
}
