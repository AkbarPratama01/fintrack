<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishLists = WishList::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('wishlists.index', compact('wishLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wishlists.create');
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
            'saved_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:planning,saving,completed,cancelled',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['saved_amount'] = $validated['saved_amount'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('wishlists', 'public');
        }

        WishList::create($validated);

        return redirect()->route('wishlists.index')
            ->with('success', 'Wish list berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WishList $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        return view('wishlists.show', compact('wishlist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WishList $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        return view('wishlists.edit', compact('wishlist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WishList $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
            'saved_amount' => 'nullable|numeric|min:0',
            'target_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:planning,saving,completed,cancelled',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($wishlist->image_url) {
                Storage::disk('public')->delete($wishlist->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('wishlists', 'public');
        }

        $wishlist->update($validated);

        return redirect()->route('wishlists.index')
            ->with('success', 'Wish list berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WishList $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete image if exists
        if ($wishlist->image_url) {
            Storage::disk('public')->delete($wishlist->image_url);
        }

        $wishlist->delete();

        return redirect()->route('wishlists.index')
            ->with('success', 'Wish list berhasil dihapus!');
    }

    /**
     * Add savings to wish list.
     */
    public function addSavings(Request $request, WishList $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $wishlist->saved_amount += $validated['amount'];
        
        // Auto update status if target reached
        if ($wishlist->saved_amount >= $wishlist->target_amount) {
            $wishlist->status = 'completed';
        } elseif ($wishlist->status === 'planning') {
            $wishlist->status = 'saving';
        }

        $wishlist->save();

        return back()->with('success', 'Tabungan berhasil ditambahkan!');
    }
}
