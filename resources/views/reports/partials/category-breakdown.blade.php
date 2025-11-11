<!-- Category Breakdown Report -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Income by Category -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Income by Category</h3>
        
        @if($reportData['incomeByCategory']->count() > 0)
            <div class="space-y-4">
                @foreach($reportData['incomeByCategory']->sortByDesc('total') as $item)
                    @php
                        $percentage = $reportData['totalIncome'] > 0 ? ($item->total / $reportData['totalIncome']) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg">{{ $item->category->icon }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item->category->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($percentage, 1) }}%</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item->count }} transactions</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between text-sm font-semibold">
                    <span class="text-gray-900 dark:text-white">Total Income</span>
                    <span class="text-green-600 dark:text-green-400">Rp {{ number_format($reportData['totalIncome'], 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No income data available</p>
            </div>
        @endif
    </div>

    <!-- Expense by Category -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Expense by Category</h3>
        
        @if($reportData['expenseByCategory']->count() > 0)
            <div class="space-y-4">
                @foreach($reportData['expenseByCategory']->sortByDesc('total') as $item)
                    @php
                        $percentage = $reportData['totalExpense'] > 0 ? ($item->total / $reportData['totalExpense']) * 100 : 0;
                        $color = $item->category->color ?? '#EF4444';
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg">{{ $item->category->icon }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item->category->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($percentage, 1) }}%</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $item->count }} transactions</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%; background-color: {{ $color }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between text-sm font-semibold">
                    <span class="text-gray-900 dark:text-white">Total Expense</span>
                    <span class="text-red-600 dark:text-red-400">Rp {{ number_format($reportData['totalExpense'], 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No expense data available</p>
            </div>
        @endif
    </div>

</div>
