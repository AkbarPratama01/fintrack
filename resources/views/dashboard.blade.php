<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ now()->format('l, F j, Y') }}
            </div>
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

            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-indigo-100">Here's what's happening with your finances today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Balance -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            @if($monthlyIncome > 0)
                                @php
                                    $balanceGrowth = $monthlyExpense > 0 ? (($monthlyIncome - $monthlyExpense) / $monthlyExpense * 100) : 0;
                                @endphp
                                <span class="text-xs font-semibold {{ $balanceGrowth >= 0 ? 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900' : 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900' }} px-2 py-1 rounded">
                                    {{ $balanceGrowth >= 0 ? '+' : '' }}{{ number_format($balanceGrowth, 1) }}%
                                </span>
                            @endif
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Balance</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Income -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded">This month</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Income</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Expenses -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 px-2 py-1 rounded">This month</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Expenses</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900 px-2 py-1 rounded">Today</span>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Transactions</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $todayTransactions }}</p>
                    </div>
                </div>
            </div>

            <!-- Charts and Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                            <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">View All</a>
                        </div>
                        @if($recentTransactions->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentTransactions as $transaction)
                                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition duration-150">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-2 {{ $transaction->type == 'income' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-lg">
                                                @if($transaction->type == 'income')
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $transaction->category->icon }} {{ $transaction->category->name }}
                                                    @if($transaction->description)
                                                        <span class="text-xs text-gray-500 dark:text-gray-400"> - {{ Str::limit($transaction->description, 20) }}</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $transaction->date->format('M d, Y') }} • {{ $transaction->wallet->name }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-semibold {{ $transaction->type == 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $transaction->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No transactions yet</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Start by adding your first income or expense</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Expense by Category</h3>
                        @if($categoryBreakdown->count() > 0 && $totalMonthlyExpense > 0)
                            <div class="space-y-4">
                                @foreach($categoryBreakdown->sortByDesc('total')->take(5) as $item)
                                    @php
                                        $percentage = ($item->total / $totalMonthlyExpense) * 100;
                                        $colors = ['#3B82F6', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444', '#EC4899', '#14B8A6'];
                                        $color = $item->category->color ?? $colors[$loop->index % count($colors)];
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $color }}"></span>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $item->category->icon }} {{ $item->category->name }}
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($percentage, 1) }}%</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%; background-color: {{ $color }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No expense data yet</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Your spending breakdown will appear here</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button onclick="openModal('incomeModal')" class="flex flex-col items-center justify-center p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-800 transition duration-150">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Add Income</span>
                        </button>

                        <button onclick="openModal('expenseModal')" class="flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900 rounded-lg hover:bg-red-100 dark:hover:bg-red-800 transition duration-150">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Add Expense</span>
                        </button>

                        <button onclick="openModal('walletModal')" class="flex flex-col items-center justify-center p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition duration-150">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Add Wallet</span>
                        </button>

                        <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition duration-150">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">View Reports</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Income Modal -->
    <div id="incomeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add Income</h3>
                <button onclick="closeModal('incomeModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="income">
                <input type="hidden" name="_modal" value="income">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="0" value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="">Select Category</option>
                        @foreach($incomeCategories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wallet</label>
                    <select name="wallet_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="">Select Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }} - {{ $wallet->formatted_balance }}
                            </option>
                        @endforeach
                    </select>
                    @error('wallet_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <input type="date" name="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" rows="3" placeholder="Add notes...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('incomeModal')" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-150">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">Save Income</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add Expense</h3>
                <button onclick="closeModal('expenseModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="type" value="expense">
                <input type="hidden" name="_modal" value="expense">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" placeholder="0" value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="">Select Category</option>
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wallet</label>
                    <select name="wallet_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="">Select Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }} - {{ $wallet->formatted_balance }}
                            </option>
                        @endforeach
                    </select>
                    @error('wallet_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <input type="date" name="date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" rows="3" placeholder="Add notes...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('expenseModal')" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-150">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">Save Expense</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Wallet Modal -->
    <div id="walletModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add Wallet</h3>
                <button onclick="closeModal('walletModal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('wallets.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_modal" value="wallet">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wallet Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white" placeholder="e.g., Main Wallet" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Initial Balance</label>
                    <input type="number" name="balance" step="0.01" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white" placeholder="0" value="{{ old('balance', 0) }}" required>
                    @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wallet Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank Account</option>
                        <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="e-wallet" {{ old('type') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="credit-card" {{ old('type') == 'credit-card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="savings" {{ old('type') == 'savings' ? 'selected' : '' }}>Savings</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                    <select name="currency" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white" required>
                        <option value="IDR" {{ old('currency', 'IDR') == 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="SGD" {{ old('currency') == 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white" rows="3" placeholder="Optional notes...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal('walletModal')" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition duration-150">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150">Create Wallet</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = ['incomeModal', 'expenseModal', 'walletModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target == modal) {
                    closeModal(modalId);
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = ['incomeModal', 'expenseModal', 'walletModal'];
                modals.forEach(modalId => {
                    closeModal(modalId);
                });
            }
        });

        // Auto-open modal if there are validation errors
        @if($errors->any())
            @if(old('_modal') == 'wallet')
                openModal('walletModal');
            @elseif(old('_modal') == 'income')
                openModal('incomeModal');
            @elseif(old('_modal') == 'expense')
                openModal('expenseModal');
            @endif
        @endif
    </script>
</x-app-layout>
