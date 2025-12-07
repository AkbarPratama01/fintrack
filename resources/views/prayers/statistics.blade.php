<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Prayer Statistics') }} 📊
            </h2>
            <a href="{{ route('prayers.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Date Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <form action="{{ route('prayers.statistics') }}" method="GET" class="flex items-end space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::parse($startDate)->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::parse($endDate)->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Overall Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Overall Statistics</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_prayers'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Prayers</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['total_days'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Days</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total_prayers'] > 0 ? number_format(($stats['total_prayers'] / ($stats['total_days'] * 5)) * 100, 1) : 0 }}%</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Completion Rate</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">On Time</span>
                                <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $stats['on_time'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['total_prayers'] > 0 ? ($stats['on_time'] / $stats['total_prayers']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Qadha</span>
                                <span class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ $stats['qadha'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $stats['total_prayers'] > 0 ? ($stats['qadha'] / $stats['total_prayers']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Missed</span>
                                <span class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $stats['missed'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $stats['total_prayers'] > 0 ? ($stats['missed'] / $stats['total_prayers']) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Jamaah</span>
                                <span class="text-lg font-semibold text-purple-600 dark:text-purple-400">{{ $stats['jamaah'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $stats['total_prayers'] > 0 ? ($stats['jamaah'] / $stats['total_prayers']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- By Prayer Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Statistics by Prayer</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @php
                            $prayerNames = [
                                ['key' => 'fajr', 'label' => 'Subuh', 'icon' => '🌅'],
                                ['key' => 'dhuhr', 'label' => 'Dzuhur', 'icon' => '☀️'],
                                ['key' => 'asr', 'label' => 'Ashar', 'icon' => '🌤️'],
                                ['key' => 'maghrib', 'label' => 'Maghrib', 'icon' => '🌆'],
                                ['key' => 'isha', 'label' => 'Isya', 'icon' => '🌙'],
                            ];
                        @endphp

                        @foreach($prayerNames as $prayer)
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-center">
                                <div class="text-3xl mb-2">{{ $prayer['icon'] }}</div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $prayer['label'] }}</h4>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['by_prayer'][$prayer['key']] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">/ {{ $stats['total_days'] }} days</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                                    {{ $stats['total_days'] > 0 ? number_format(($stats['by_prayer'][$prayer['key']] / $stats['total_days']) * 100, 1) : 0 }}%
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Prayer History Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Prayer History</h3>
                    
                    @if($prayers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prayer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jamaah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($prayers->sortByDesc('prayer_date') as $prayer)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $prayer->prayer_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $prayer->getPrayerNameIndonesian() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $prayer->getStatusColor() === 'green' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' : ($prayer->getStatusColor() === 'yellow' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200') }}">
                                                    {{ $prayer->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                @if($prayer->is_jamaah)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-200">
                                                        Yes
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $prayer->prayed_at ? $prayer->prayed_at->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No prayer records found for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
