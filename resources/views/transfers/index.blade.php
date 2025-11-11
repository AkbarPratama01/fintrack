<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Wallet Transfer') }}
            </h2>
            <button onclick="toggleModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <span>New Transfer</span>
            </button>
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Transfers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded">All Time</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Transfers</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTransfers }}</p>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900 px-2 py-1 rounded">All Time</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Amount</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Monthly Transfers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 px-2 py-1 rounded">This Month</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Monthly Transfers</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $thisMonthTransfers }}</p>
                    </div>
                </div>

                <!-- Monthly Amount -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900 px-2 py-1 rounded">This Month</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Monthly Amount</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($thisMonthAmount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Transfer History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transfer History</h3>
                    </div>

                    @if($transfers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">From</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">→</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">To</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transfers as $transfer)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $transfer->transfer_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <div class="p-1 bg-red-100 dark:bg-red-900 rounded">
                                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->fromWallet->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <svg class="w-5 h-5 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex items-center space-x-2">
                                                    <div class="p-1 bg-green-100 dark:bg-green-900 rounded">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-gray-900 dark:text-white font-medium">{{ $transfer->toWallet->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transfer->description ? Str::limit($transfer->description, 30) : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-indigo-600 dark:text-indigo-400">
                                                Rp {{ number_format($transfer->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('transfers.destroy', $transfer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this transfer? The wallet balances will be reverted.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $transfers->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No transfers yet</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Start by creating your first wallet transfer</p>
                            <button onclick="toggleModal()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                                New Transfer
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Transfer Modal -->
    <div id="transferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">New Transfer</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('transfers.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_modal" value="transfer">

                <!-- From Wallet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Wallet</label>
                    <select name="from_wallet_id" id="from_wallet_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('from_wallet_id') ? 'border-red-500' : '' }}" required onchange="updateToWalletOptions()">
                        <option value="">Select Source Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" data-balance="{{ $wallet->balance }}" {{ old('from_wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }} ({{ $wallet->formatted_balance }})
                            </option>
                        @endforeach
                    </select>
                    <p id="balance_info" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                    @error('from_wallet_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- To Wallet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Wallet</label>
                    <select name="to_wallet_id" id="to_wallet_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('to_wallet_id') ? 'border-red-500' : '' }}" required>
                        <option value="">Select Destination Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" data-from="{{ $wallet->id }}" {{ old('to_wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }} ({{ $wallet->formatted_balance }})
                            </option>
                        @endforeach
                    </select>
                    @error('to_wallet_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('amount') ? 'border-red-500' : '' }}" placeholder="0" value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transfer Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transfer Date</label>
                    <input type="date" name="transfer_date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white {{ $errors->has('transfer_date') ? 'border-red-500' : '' }}" value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                    @error('transfer_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea name="description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" rows="3" placeholder="Add notes...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-150">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">Transfer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            document.getElementById('transferModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('transferModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function updateToWalletOptions() {
            const fromWalletId = document.getElementById('from_wallet_id').value;
            const fromWalletSelect = document.getElementById('from_wallet_id');
            const selectedOption = fromWalletSelect.options[fromWalletSelect.selectedIndex];
            const balance = selectedOption.getAttribute('data-balance');
            
            const toWalletSelect = document.getElementById('to_wallet_id');
            const options = toWalletSelect.getElementsByTagName('option');
            
            // Show balance info
            if (balance) {
                document.getElementById('balance_info').textContent = `Available balance: Rp ${parseFloat(balance).toLocaleString('id-ID')}`;
            } else {
                document.getElementById('balance_info').textContent = '';
            }
            
            // Disable the selected from_wallet in to_wallet dropdown
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === fromWalletId) {
                    options[i].disabled = true;
                    options[i].style.display = 'none';
                } else {
                    options[i].disabled = false;
                    options[i].style.display = 'block';
                }
            }
            
            // Reset to_wallet if it's the same as from_wallet
            if (toWalletSelect.value === fromWalletId) {
                toWalletSelect.value = '';
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('transferModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Auto-open modal if there are validation errors
        @if($errors->any() && old('_modal') == 'transfer')
            toggleModal();
            updateToWalletOptions();
        @endif

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateToWalletOptions();
        });
    </script>
</x-app-layout>
