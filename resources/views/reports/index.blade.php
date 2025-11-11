<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Generate Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Report Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Report Type <span class="text-red-500">*</span>
                            </label>
                            <select name="report_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Select Report Type</option>
                                <option value="income-expense" {{ old('report_type') == 'income-expense' ? 'selected' : '' }}>Income vs Expense</option>
                                <option value="category-breakdown" {{ old('report_type') == 'category-breakdown' ? 'selected' : '' }}>Category Breakdown</option>
                                <option value="monthly-summary" {{ old('report_type') == 'monthly-summary' ? 'selected' : '' }}>Monthly Summary</option>
                                <option value="wallet-balance" {{ old('report_type') == 'wallet-balance' ? 'selected' : '' }}>Wallet Balance</option>
                                <option value="cash-flow" {{ old('report_type') == 'cash-flow' ? 'selected' : '' }}>Cash Flow</option>
                            </select>
                            @error('report_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Period -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Period <span class="text-red-500">*</span>
                            </label>
                            <select name="period" id="period" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white" required onchange="toggleCustomDates()">
                                <option value="">Select Period</option>
                                <option value="this-month" {{ old('period') == 'this-month' ? 'selected' : '' }}>This Month</option>
                                <option value="last-month" {{ old('period') == 'last-month' ? 'selected' : '' }}>Last Month</option>
                                <option value="last-3-months" {{ old('period') == 'last-3-months' ? 'selected' : '' }}>Last 3 Months</option>
                                <option value="last-6-months" {{ old('period') == 'last-6-months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="this-year" {{ old('period') == 'this-year' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ old('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                            @error('period')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Custom Date Range -->
                        <div id="customDates" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('period') != 'custom' ? 'hidden' : '' }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Select Wallets -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Wallets
                            </label>
                            <div class="space-y-2 max-h-48 overflow-y-auto p-4 border border-gray-300 dark:border-gray-600 rounded-lg">
                                <label class="flex items-center">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" onchange="toggleAllWallets(this)">
                                    <span class="ml-2 text-sm font-semibold text-gray-700 dark:text-gray-300">All Wallets</span>
                                </label>
                                @forelse($wallets as $wallet)
                                    <label class="flex items-center wallet-checkbox">
                                        <input type="checkbox" name="wallets[]" value="{{ $wallet->id }}" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" {{ in_array($wallet->id, old('wallets', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $wallet->name }} - {{ $wallet->formatted_balance }}
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No wallets available. Please create a wallet first.</p>
                                @endforelse
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave unchecked to include all wallets</p>
                            @error('wallets')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Export Format (for future use) -->
                        <!--
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Export Format
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="html" class="border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" checked>
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">View Online</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="pdf" class="border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">PDF</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="excel" class="border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Excel</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="format" value="csv" class="border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">CSV</span>
                                </label>
                            </div>
                        </div>
                        -->

                        <!-- Submit Buttons -->
                        <div class="flex space-x-3 pt-4">
                            <a href="{{ route('dashboard') }}" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-150 text-center">
                                Cancel
                            </a>
                            <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-150">
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCustomDates() {
            const period = document.getElementById('period').value;
            const customDates = document.getElementById('customDates');
            
            if (period === 'custom') {
                customDates.classList.remove('hidden');
            } else {
                customDates.classList.add('hidden');
            }
        }

        function toggleAllWallets(checkbox) {
            const walletCheckboxes = document.querySelectorAll('.wallet-checkbox input[type="checkbox"]');
            walletCheckboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
        }

        // Update "Select All" checkbox state when individual checkboxes change
        document.addEventListener('DOMContentLoaded', function() {
            const walletCheckboxes = document.querySelectorAll('.wallet-checkbox input[type="checkbox"]');
            const selectAllCheckbox = document.getElementById('selectAll');
            
            walletCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(walletCheckboxes).every(checkbox => checkbox.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });
        });
    </script>
</x-app-layout>
