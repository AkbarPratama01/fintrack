<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Wish List') }}
            </h2>
            <a href="{{ route('wishlists.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Wish</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Wish Lists Grid -->
            @if($wishLists->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishLists as $wishlist)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
                            @if($wishlist->image_url)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ asset('storage/' . $wishlist->image_url) }}" alt="{{ $wishlist->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex-1 line-clamp-1">
                                        {{ $wishlist->name }}
                                    </h3>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $wishlist->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($wishlist->status === 'saving' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($wishlist->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200')) }}">
                                        {{ ucfirst($wishlist->status) }}
                                    </span>
                                </div>

                                @if($wishlist->description)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $wishlist->description }}</p>
                                @endif

                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <span>Progress</span>
                                        <span class="font-semibold">{{ number_format($wishlist->progress_percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ min($wishlist->progress_percentage, 100) }}%"></div>
                                    </div>
                                </div>

                                <div class="space-y-2 mb-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Target:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($wishlist->target_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Saved:</span>
                                        <span class="font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($wishlist->saved_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
                                        <span class="font-semibold text-orange-600 dark:text-orange-400">Rp {{ number_format($wishlist->remaining_amount, 0, ',', '.') }}</span>
                                    </div>
                                    @if($wishlist->target_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Target Date:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $wishlist->target_date->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if($wishlist->status !== 'completed' && $wishlist->status !== 'cancelled')
                                        <button onclick="openAddSavingsModal({{ $wishlist->id }}, '{{ $wishlist->name }}')" class="flex-1 px-3 py-2 text-sm bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 rounded transition">
                                            Add Savings
                                        </button>
                                    @endif
                                    <a href="{{ route('wishlists.edit', $wishlist) }}" class="px-3 py-2 text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 rounded transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('wishlists.destroy', $wishlist) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this wish list?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 text-sm bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-200 rounded transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $wishLists->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No wish lists yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Start planning for your dreams and goals!</p>
                    <a href="{{ route('wishlists.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Wish List
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Savings Modal -->
    <div id="addSavingsModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Savings</h3>
                    <button onclick="closeAddSavingsModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4" id="wishlistName"></p>
                <form id="addSavingsForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            required>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeAddSavingsModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Add Savings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddSavingsModal(id, name) {
            document.getElementById('wishlistName').textContent = 'Adding savings to: ' + name;
            document.getElementById('addSavingsForm').action = `/wishlists/${id}/add-savings`;
            document.getElementById('addSavingsModal').classList.remove('hidden');
        }

        function closeAddSavingsModal() {
            document.getElementById('addSavingsModal').classList.add('hidden');
            document.getElementById('amount').value = '';
        }
    </script>
</x-app-layout>
