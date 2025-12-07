<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Prayer Tracker') }} 🕌
            </h2>
            <a href="{{ route('prayers.statistics') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Statistics</span>
            </a>
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total This Month</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">On Time</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['on_time'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Qadha</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['qadha'] }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Jamaah</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['jamaah'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Today's Prayer Times -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Today's Prayer Times</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $prayerTimes['location'] ?? 'Jakarta, Indonesia' }} - {{ \Carbon\Carbon::today()->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @php
                            $prayers = [
                                ['name' => 'fajr', 'label' => 'Subuh', 'icon' => '🌅'],
                                ['name' => 'dhuhr', 'label' => 'Dzuhur', 'icon' => '☀️'],
                                ['name' => 'asr', 'label' => 'Ashar', 'icon' => '🌤️'],
                                ['name' => 'maghrib', 'label' => 'Maghrib', 'icon' => '🌆'],
                                ['name' => 'isha', 'label' => 'Isya', 'icon' => '🌙'],
                            ];
                        @endphp

                        @foreach($prayers as $prayer)
                            @php
                                $tracked = $todayPrayers->get($prayer['name']);
                            @endphp
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $tracked ? 'bg-green-50 dark:bg-green-900 border-green-300 dark:border-green-700' : '' }}">
                                <div class="text-center mb-3">
                                    <div class="text-3xl mb-2">{{ $prayer['icon'] }}</div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $prayer['label'] }}</h4>
                                    <p class="text-lg font-mono text-gray-600 dark:text-gray-300">{{ $prayerTimes[$prayer['name']] ?? '-' }}</p>
                                </div>

                                @if($tracked)
                                    <div class="text-center">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $tracked->getStatusColor() === 'green' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' : ($tracked->getStatusColor() === 'yellow' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200') }}">
                                            {{ $tracked->getStatusLabel() }}
                                        </span>
                                        @if($tracked->is_jamaah)
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-200 ml-1">
                                                Jamaah
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <button onclick="openPrayerModal('{{ $prayer['name'] }}', '{{ $prayer['label'] }}')" class="w-full mt-2 px-3 py-2 text-sm bg-indigo-100 text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-200 rounded transition">
                                        Track Prayer
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Prayer History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Prayer History (Last 7 Days)</h3>
                    
                    @if($recentPrayers->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPrayers as $date => $dayPrayers)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                        @foreach($dayPrayers as $prayer)
                                            <div class="text-center p-2 rounded {{ $prayer->getStatusColor() === 'green' ? 'bg-green-50 dark:bg-green-900' : ($prayer->getStatusColor() === 'yellow' ? 'bg-yellow-50 dark:bg-yellow-900' : 'bg-red-50 dark:bg-red-900') }}">
                                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $prayer->getPrayerNameIndonesian() }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $prayer->getStatusLabel() }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No prayer records yet. Start tracking your prayers today!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Prayer Tracking Modal -->
    <div id="prayerModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Track Prayer</h3>
                    <button onclick="closePrayerModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="prayerForm" action="{{ route('prayers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="prayer_name" id="prayer_name">
                    <input type="hidden" name="prayer_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">

                    <div class="mb-4">
                        <p class="text-gray-700 dark:text-gray-300 mb-2">Prayer: <span id="prayer_label" class="font-semibold"></span></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="on_time">Tepat Waktu</option>
                            <option value="qadha">Qadha</option>
                            <option value="missed">Terlewat</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_jamaah" value="1" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Sholat berjamaah</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closePrayerModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Track Prayer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPrayerModal(prayerName, prayerLabel) {
            document.getElementById('prayer_name').value = prayerName;
            document.getElementById('prayer_label').textContent = prayerLabel;
            document.getElementById('prayerModal').classList.remove('hidden');
        }

        function closePrayerModal() {
            document.getElementById('prayerModal').classList.add('hidden');
            document.getElementById('prayerForm').reset();
        }
    </script>
</x-app-layout>
