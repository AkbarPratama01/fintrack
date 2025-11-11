<!-- Monthly Summary Report -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Monthly Summary</h3>
    
    @if($reportData['monthlyData']->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Income</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expense</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Net</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Savings Rate</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transactions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reportData['monthlyData'] as $month)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400">
                                Rp {{ number_format($month->income, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">
                                Rp {{ number_format($month->expense, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $month->net >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                Rp {{ number_format($month->net, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                {{ number_format($month->savings_rate, 1) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                {{ $month->transaction_count }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-900">
                    <tr class="font-semibold">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Total / Average</td>
                        <td class="px-6 py-4 text-sm text-right text-green-600 dark:text-green-400">
                            Rp {{ number_format($reportData['monthlyData']->sum('income'), 0, ',', '.') }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">Avg: Rp {{ number_format($reportData['averageIncome'], 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-red-600 dark:text-red-400">
                            Rp {{ number_format($reportData['monthlyData']->sum('expense'), 0, ',', '.') }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">Avg: Rp {{ number_format($reportData['averageExpense'], 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-right {{ $reportData['monthlyData']->sum('net') >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                            Rp {{ number_format($reportData['monthlyData']->sum('net'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">
                            {{ number_format($reportData['monthlyData']->avg('savings_rate'), 1) }}%
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-gray-500 dark:text-gray-400">
                            {{ $reportData['monthlyData']->sum('transaction_count') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Chart -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Monthly Trend</h4>
            <div class="space-y-3">
                @foreach($reportData['monthlyData'] as $month)
                    <div>
                        <div class="flex items-center justify-between mb-1 text-xs">
                            <span class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($month->month . '-01')->format('M Y') }}</span>
                            <span class="text-gray-900 dark:text-white">Net: Rp {{ number_format($month->net, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex space-x-1 h-4">
                            @php
                                $total = $month->income + $month->expense;
                                $incomeWidth = $total > 0 ? ($month->income / $total) * 100 : 0;
                                $expenseWidth = $total > 0 ? ($month->expense / $total) * 100 : 0;
                            @endphp
                            <div class="bg-green-500 rounded" style="width: {{ $incomeWidth }}%"></div>
                            <div class="bg-red-500 rounded" style="width: {{ $expenseWidth }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400">No data available for this period</p>
        </div>
    @endif
</div>
