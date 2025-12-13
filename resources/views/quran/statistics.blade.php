<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quran Reading Statistics') }} 📊
            </h2>
            <a href="{{ route('quran-readings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150 flex items-center space-x-2">
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
                    <form action="{{ route('quran-readings.statistics') }}" method="GET" class="flex items-end space-x-4">
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['total_ayahs']) }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Ayahs Read</p>
                        </div>
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_readings'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Reading Sessions</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['days_read'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Days Read</p>
                        </div>
                        <div class="text-center p-4 bg-orange-50 dark:bg-orange-900 rounded-lg">
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['unique_surahs'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Unique Surahs</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Average Per Day</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['average_ayahs_per_day'] }} ayahs</p>
                        </div>

                        @if($stats['most_read_surah'])
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Most Read Surah</p>
                                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $stats['most_read_surah']['name'] }}
                                    <span class="text-sm text-gray-500">({{ $stats['most_read_surah']['count'] }}x)</span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Juz Progress -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Juz Progress</h3>
                    
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Estimated Juz Completed</span>
                            <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $juzProgress }} / 30</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full" style="width: {{ ($juzProgress / 30) * 100 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ round(($juzProgress / 30) * 100, 2) }}% of 30 Juz</p>
                    </div>

                    <div class="grid grid-cols-6 md:grid-cols-10 gap-2 mt-6">
                        @for($i = 1; $i <= 30; $i++)
                            <div class="text-center p-2 rounded {{ $i <= floor($juzProgress) ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                <span class="text-xs font-semibold">{{ $i }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Reading History Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Reading History</h3>
                    
                    @if(count($readings) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Surah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ayah Range</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Ayahs</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Duration</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($readings as $reading)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $reading->reading_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $reading->getSurahNameLatin() }} ({{ $reading->surah_number }})
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ $reading->from_ayah }} - {{ $reading->to_ayah }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                                    {{ $reading->total_ayahs_read }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $reading->duration ? \Carbon\Carbon::parse($reading->duration)->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No reading records found for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daily Reading Chart -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Daily Reading Activity</h3>
                    
                    <div class="space-y-3">
                        @php
                            $dailyStats = $readings->groupBy(function($reading) {
                                return $reading->reading_date->format('Y-m-d');
                            })->map(function($day) {
                                return [
                                    'date' => $day->first()->reading_date,
                                    'total' => $day->sum('total_ayahs_read'),
                                    'sessions' => $day->count()
                                ];
                            })->sortByDesc('date');
                            
                            $maxAyahs = $dailyStats->max('total') ?: 1;
                        @endphp

                        @foreach($dailyStats->take(14) as $stat)
                            <div class="flex items-center space-x-3">
                                <div class="w-32 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $stat['date']->format('d M Y') }}
                                </div>
                                <div class="flex-1">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-6 relative">
                                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-6 rounded-full flex items-center justify-end pr-2" 
                                             style="width: {{ ($stat['total'] / $maxAyahs) * 100 }}%">
                                            <span class="text-xs text-white font-semibold">{{ $stat['total'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-20 text-sm text-gray-500 dark:text-gray-400 text-right">
                                    {{ $stat['sessions'] }} session{{ $stat['sessions'] > 1 ? 's' : '' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
