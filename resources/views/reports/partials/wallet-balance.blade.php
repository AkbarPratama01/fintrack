<!-- Wallet Balance Report -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Balance -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Balance</h4>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($reportData['totalBalance'], 0, ',', '.') }}</p>
    </div>

    <!-- Period Income -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Period Income</h4>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($reportData['totalIncome'], 0, ',', '.') }}</p>
    </div>

    <!-- Period Expense -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Period Expense</h4>
        <p class="text-2xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($reportData['totalExpense'], 0, ',', '.') }}</p>
    </div>
</div>

<!-- Wallet Details -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Wallet Details</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Wallet</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Balance</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period Income</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period Expense</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transactions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($reportData['wallets'] as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['wallet']->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item['wallet']->currency }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item['wallet']->type === 'bank') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($item['wallet']->type === 'cash') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($item['wallet']->type === 'e-wallet') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @elseif($item['wallet']->type === 'credit-card') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endif">
                                {{ ucfirst($item['wallet']->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($item['currentBalance'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400">
                            Rp {{ number_format($item['incomeTotal'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">
                            Rp {{ number_format($item['expenseTotal'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                            {{ $item['transactionCount'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Balance Distribution Chart -->
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Balance Distribution</h4>
        <div class="space-y-3">
            @foreach($reportData['wallets'] as $item)
                @php
                    $percentage = $reportData['totalBalance'] > 0 ? ($item['currentBalance'] / $reportData['totalBalance']) * 100 : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item['wallet']->name }}</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
