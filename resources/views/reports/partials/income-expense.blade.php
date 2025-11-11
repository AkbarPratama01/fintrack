<!-- Income vs Expense Report -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Total Income -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Income</h4>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($reportData['totalIncome'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Total Expense -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
            <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Expense</h4>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($reportData['totalExpense'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Net Income -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 {{ $reportData['netIncome'] >= 0 ? 'bg-blue-100 dark:bg-blue-900' : 'bg-red-100 dark:bg-red-900' }} rounded-lg">
                    <svg class="w-6 h-6 {{ $reportData['netIncome'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Net Income</h4>
            <p class="text-2xl font-bold {{ $reportData['netIncome'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                Rp {{ number_format($reportData['netIncome'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Savings Rate -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Savings Rate</h4>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($reportData['savingsRate'], 1) }}%</p>
        </div>
    </div>
</div>

<!-- Daily Chart -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Daily Income vs Expense</h3>
    
    @if($reportData['dailyData']->count() > 0)
        <div class="space-y-3">
            @foreach($reportData['dailyData'] as $day)
                <div>
                    <div class="flex items-center justify-between mb-1 text-sm">
                        <span class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($day->day)->format('M d, Y') }}</span>
                        <div class="space-x-4">
                            <span class="text-green-600 dark:text-green-400">+Rp {{ number_format($day->income, 0, ',', '.') }}</span>
                            <span class="text-red-600 dark:text-red-400">-Rp {{ number_format($day->expense, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-1 h-6">
                        @php
                            $total = $day->income + $day->expense;
                            $incomeWidth = $total > 0 ? ($day->income / $total) * 100 : 0;
                            $expenseWidth = $total > 0 ? ($day->expense / $total) * 100 : 0;
                        @endphp
                        <div class="bg-green-500 rounded" style="width: {{ $incomeWidth }}%" title="Income: Rp {{ number_format($day->income, 0, ',', '.') }}"></div>
                        <div class="bg-red-500 rounded" style="width: {{ $expenseWidth }}%" title="Expense: Rp {{ number_format($day->expense, 0, ',', '.') }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400">No data available for this period</p>
        </div>
    @endif
</div>

<!-- Summary -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Summary</h3>
    <div class="space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Total Transactions:</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $reportData['transactionCount'] }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Average Daily Income:</span>
            <span class="font-semibold text-green-600 dark:text-green-400">
                Rp {{ number_format($reportData['totalIncome'] / max($reportData['dailyData']->count(), 1), 0, ',', '.') }}
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Average Daily Expense:</span>
            <span class="font-semibold text-red-600 dark:text-red-400">
                Rp {{ number_format($reportData['totalExpense'] / max($reportData['dailyData']->count(), 1), 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>
