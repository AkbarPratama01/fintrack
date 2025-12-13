<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quran Reading Tracker') }} 📖
            </h2>
            <div class="flex flex-wrap gap-2 sm:space-x-3">
                <a href="{{ route('quran-readings.read') }}" class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-150 flex items-center justify-center space-x-2 text-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="hidden sm:inline">Read Quran</span>
                    <span class="sm:hidden">Read</span>
                </a>
                <a href="{{ route('quran-readings.statistics') }}" class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center justify-center space-x-2 text-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="hidden sm:inline">Statistics</span>
                    <span class="sm:hidden">Stats</span>
                </a>
                <button onclick="openReadingModal()" class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150 flex items-center justify-center space-x-2 text-sm">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden sm:inline">Track Reading</span>
                    <span class="sm:hidden">Track</span>
                </button>
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-sm font-medium mb-1 opacity-90">Total Ayahs Read</h4>
                        <p class="text-3xl font-bold">{{ number_format($totalAyahsRead) }}</p>
                        <p class="text-xs mt-2 opacity-75">{{ $progressPercentage }}% of Quran</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-sm font-medium mb-1 opacity-90">This Month</h4>
                        <p class="text-3xl font-bold">{{ $monthlyStats['total_readings'] }}</p>
                        <p class="text-xs mt-2 opacity-75">{{ number_format($monthlyStats['total_ayahs']) }} ayahs</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-sm font-medium mb-1 opacity-90">Reading Streak</h4>
                        <p class="text-3xl font-bold">{{ $streak }}</p>
                        <p class="text-xs mt-2 opacity-75">{{ $streak === 1 ? 'day' : 'days' }} in a row</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-white/20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-sm font-medium mb-1 opacity-90">Unique Surahs</h4>
                        <p class="text-3xl font-bold">{{ $monthlyStats['unique_surahs'] }}</p>
                        <p class="text-xs mt-2 opacity-75">out of 114 surahs</p>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Overall Progress</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ number_format($totalAyahsRead) }} / 6,236 ayahs</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-4 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $progressPercentage }}% completed</p>
                </div>
            </div>

            <!-- Today's Readings -->
            @if($todayReadings->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Today's Readings - {{ \Carbon\Carbon::today()->format('d F Y') }}</h3>
                        
                        <div class="space-y-3">
                            @foreach($todayReadings as $reading)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-2xl">📖</span>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $reading->getSurahNameLatin() }} 
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">({{ $reading->getSurahNameArabic() }})</span>
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Ayah {{ $reading->from_ayah }} - {{ $reading->to_ayah }} 
                                                    <span class="ml-2 text-green-600 dark:text-green-400 font-semibold">({{ $reading->total_ayahs_read }} ayahs)</span>
                                                </p>
                                                @if($reading->notes)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">📝 {{ $reading->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModal({{ $reading->id }}, {{ $reading->surah_number }}, {{ $reading->from_ayah }}, {{ $reading->to_ayah }}, '{{ $reading->duration }}', '{{ $reading->notes }}')" 
                                            class="ml-4 px-3 py-2 text-sm bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 rounded transition">
                                        Edit
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Reading History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Reading History (Last 7 Days)</h3>
                    
                    @if($recentReadings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentReadings as $date => $dayReadings)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</h4>
                                        <span class="text-sm bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full">
                                            {{ $dayReadings->sum('total_ayahs_read') }} ayahs
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($dayReadings as $reading)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg group">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $reading->getSurahNameLatin() }} ({{ $reading->surah_number }}) - Ayah {{ $reading->from_ayah }}-{{ $reading->to_ayah }}
                                                    </p>
                                                    @if($reading->duration)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">⏱️ {{ \Carbon\Carbon::parse($reading->duration)->format('H:i') }}</p>
                                                    @endif
                                                </div>
                                                <button onclick="openEditModal({{ $reading->id }}, {{ $reading->surah_number }}, {{ $reading->from_ayah }}, {{ $reading->to_ayah }}, '{{ $reading->duration }}', '{{ $reading->notes }}')" 
                                                        class="opacity-0 group-hover:opacity-100 transition-opacity ml-2 p-2 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No reading records yet. Start tracking your Quran reading today!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Reading Modal -->
    <div id="readingModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Track Reading</h3>
                    <button onclick="closeReadingModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="readingForm" action="{{ route('quran-readings.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Surah</label>
                        <select name="surah_number" id="surah_select" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="">Select Surah</option>
                            @foreach($surahs as $number => $surah)
                                <option value="{{ $number }}">{{ $number }}. {{ $surah['latin'] }} - {{ $surah['arabic'] }} ({{ $surah['total_ayahs'] }} ayahs)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Ayah</label>
                            <input type="number" name="from_ayah" id="from_ayah" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Ayah</label>
                            <input type="number" name="to_ayah" id="to_ayah" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                        <input type="date" name="reading_date" id="reading_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (Optional)</label>
                        <input type="time" name="duration" id="duration" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeReadingModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Track Reading
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Reading Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Reading</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editForm" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Surah</label>
                        <select name="surah_number" id="edit_surah" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                            @foreach($surahs as $number => $surah)
                                <option value="{{ $number }}">{{ $number }}. {{ $surah['latin'] }} - {{ $surah['arabic'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Ayah</label>
                            <input type="number" name="from_ayah" id="edit_from_ayah" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Ayah</label>
                            <input type="number" name="to_ayah" id="edit_to_ayah" min="1" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (Optional)</label>
                        <input type="time" name="duration" id="edit_duration" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" id="edit_notes" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" onclick="deleteReading()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Delete
                        </button>
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentReadingId = null;

        function openReadingModal() {
            document.getElementById('readingModal').classList.remove('hidden');
        }

        function closeReadingModal() {
            document.getElementById('readingModal').classList.add('hidden');
            document.getElementById('readingForm').reset();
        }

        function openEditModal(id, surah, fromAyah, toAyah, duration, notes) {
            currentReadingId = id;
            document.getElementById('edit_surah').value = surah;
            document.getElementById('edit_from_ayah').value = fromAyah;
            document.getElementById('edit_to_ayah').value = toAyah;
            document.getElementById('edit_duration').value = duration || '';
            document.getElementById('edit_notes').value = notes || '';
            document.getElementById('editForm').action = `/quran-readings/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            currentReadingId = null;
        }

        function deleteReading() {
            if (confirm('Are you sure you want to delete this reading record?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/quran-readings/${currentReadingId}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
