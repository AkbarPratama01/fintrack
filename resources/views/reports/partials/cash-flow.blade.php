<!-- Cash Flow Report -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Opening Balance -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Opening Balance</h4>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($reportData['openingBalance'], 0, ',', '.') }}</p>
    </div>

    <!-- Cash Inflow -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Cash Inflow</h4>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($reportData['periodIncome'], 0, ',', '.') }}</p>
    </div>

    <!-- Cash Outflow -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Cash Outflow</h4>
        <p class="text-2xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($reportData['periodExpense'], 0, ',', '.') }}</p>
    </div>

    <!-- Closing Balance -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Closing Balance</h4>
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($reportData['closingBalance'], 0, ',', '.') }}</p>
    </div>
</div>

<!-- Cash Flow Statement -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Cash Flow Statement</h3>
    
    <div class="space-y-4">
        <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
            <span class="text-sm font-medium text-gray-900 dark:text-white">Opening Balance</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format($reportData['openingBalance'], 0, ',', '.') }}</span>
        </div>

        <div class="pl-4 space-y-2">
            <div class="flex justify-between items-center py-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Cash Inflow (Income)</span>
                <span class="text-sm text-green-600 dark:text-green-400">+Rp {{ number_format($reportData['periodIncome'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Cash Outflow (Expense)</span>
                <span class="text-sm text-red-600 dark:text-red-400">-Rp {{ number_format($reportData['periodExpense'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
            <span class="text-sm font-medium text-gray-900 dark:text-white">Net Cash Flow</span>
            <span class="text-sm font-semibold {{ $reportData['netCashFlow'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $reportData['netCashFlow'] >= 0 ? '+' : '' }}Rp {{ number_format($reportData['netCashFlow'], 0, ',', '.') }}
            </span>
        </div>

        <div class="flex justify-between items-center py-2 border-t-2 border-gray-300 dark:border-gray-600 font-semibold">
            <span class="text-sm text-gray-900 dark:text-white">Closing Balance</span>
            <span class="text-sm text-blue-600 dark:text-blue-400">Rp {{ number_format($reportData['closingBalance'], 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<!-- Daily Cash Flow -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Daily Cash Flow</h3>
    
    @if($reportData['dailyCashFlow']->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inflow</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Outflow</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Net Flow</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visual</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reportData['dailyCashFlow'] as $day)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($day->day)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400">
                                Rp {{ number_format($day->inflow, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">
                                Rp {{ number_format($day->outflow, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $day->net_flow >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $day->net_flow >= 0 ? '+' : '' }}Rp {{ number_format($day->net_flow, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $maxAbsFlow = $reportData['dailyCashFlow']->max(fn($d) => abs($d->net_flow));
                                    $barWidth = $maxAbsFlow > 0 ? (abs($day->net_flow) / $maxAbsFlow) * 100 : 0;
                                @endphp
                                <div class="flex items-center justify-end">
                                    <div class="h-2 rounded {{ $day->net_flow >= 0 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ $barWidth }}%; min-width: 2px;"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400">No cash flow data available for this period</p>
        </div>
    @endif
</div>
