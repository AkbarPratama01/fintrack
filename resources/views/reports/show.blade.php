<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Report') }}: {{ ucwords(str_replace('-', ' ', $reportType)) }}
            </h2>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150">
                New Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Report Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Period:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-2">
                            {{ ucwords(str_replace('-', ' ', $period)) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Date Range:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-2">
                            {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Wallets:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-2">
                            {{ $wallets->count() }} wallet(s)
                        </span>
                    </div>
                </div>
            </div>

            @if($reportType === 'income-expense')
                @include('reports.partials.income-expense')
            @elseif($reportType === 'category-breakdown')
                @include('reports.partials.category-breakdown')
            @elseif($reportType === 'monthly-summary')
                @include('reports.partials.monthly-summary')
            @elseif($reportType === 'wallet-balance')
                @include('reports.partials.wallet-balance')
            @elseif($reportType === 'cash-flow')
                @include('reports.partials.cash-flow')
            @endif

        </div>
    </div>
</x-app-layout>
