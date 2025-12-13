<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Read Al-Quran') }} 📖
            </h2>
            <a href="{{ route('quran-readings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Tracker</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Surah List Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden sticky top-4">
                        <div class="p-4 bg-gradient-to-r from-green-600 to-green-700 text-white">
                            <h3 class="font-semibold">Daftar Surah</h3>
                        </div>
                        <div class="overflow-y-auto" style="max-height: calc(100vh - 250px);">
                            @php
                                $surahs = \App\Models\QuranReading::SURAHS;
                            @endphp
                            @foreach($surahs as $number => $surah)
                                <a href="{{ route('quran-readings.read', ['surah' => $number]) }}" 
                                   class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $number == $surahNumber ? 'bg-green-50 dark:bg-green-900 border-l-4 border-green-600' : '' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-sm font-semibold">
                                            {{ $number }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $surah['latin'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $surah['indonesian'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-lg text-gray-700 dark:text-gray-300">{{ $surah['arabic'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Surah Content -->
                <div class="lg:col-span-3 space-y-6">
                    
                    <!-- Surah Header -->
                    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6 text-center">
                            <h1 class="text-3xl font-bold mb-2">{{ $surahData['name'] ?? '' }}</h1>
                            <h2 class="text-2xl mb-3 opacity-90">{{ $surahData['englishName'] ?? '' }}</h2>
                            <p class="text-sm opacity-75">{{ $surahData['englishNameTranslation'] ?? '' }}</p>
                            <div class="mt-4 flex items-center justify-center space-x-6 text-sm">
                                <span>📍 {{ $surahData['revelationType'] ?? '' }}</span>
                                <span>📖 {{ $surahData['numberOfAyahs'] ?? 0 }} Ayat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bismillah (except for Surah 9) -->
                    @if($surahNumber != 9 && $surahNumber != 1)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
                            <p class="text-4xl text-gray-800 dark:text-gray-200" style="font-family: 'Amiri', 'Traditional Arabic', serif; line-height: 2;">
                                بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Dengan nama Allah Yang Maha Pengasih, Maha Penyayang</p>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4">
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <button onclick="trackFullSurah()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Track Full Surah</span>
                            </button>
                            
                            <div class="flex items-center space-x-2">
                                <label class="text-sm text-gray-600 dark:text-gray-400">Font Size:</label>
                                <button onclick="changeFontSize(-2)" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">A-</button>
                                <button onclick="changeFontSize(2)" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">A+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Ayahs -->
                    <div class="space-y-4">
                        @if(isset($surahData['ayahs']) && isset($arabicData['ayahs']))
                            @foreach($surahData['ayahs'] as $index => $ayah)
                                <div id="ayah-{{ $ayah['numberInSurah'] }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                                    <!-- Ayah Number Header -->
                                    <div class="flex items-center justify-between px-6 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-semibold">
                                                {{ $ayah['numberInSurah'] }}
                                            </div>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Ayah {{ $ayah['numberInSurah'] }} of {{ $surahData['numberOfAyahs'] }}</span>
                                        </div>
                                        <button onclick="trackSingleAyah({{ $ayah['numberInSurah'] }})" class="px-3 py-1 text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded hover:bg-green-200 dark:hover:bg-green-800 transition">
                                            ✓ Track
                                        </button>
                                    </div>

                                    <!-- Arabic Text -->
                                    <div class="p-6 text-right" dir="rtl">
                                        <p class="ayah-text text-3xl text-gray-800 dark:text-gray-200 leading-loose" style="font-family: 'Amiri', 'Traditional Arabic', serif;">
                                            {{ $arabicData['ayahs'][$index]['text'] ?? '' }}
                                        </p>
                                    </div>

                                    <!-- Indonesian Translation -->
                                    <div class="px-6 pb-6">
                                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                            <p class="text-gray-700 dark:text-gray-300 text-base leading-relaxed">
                                                <span class="font-semibold text-green-700 dark:text-green-400">{{ $ayah['numberInSurah'] }}.</span>
                                                {{ $ayah['text'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Navigation -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4">
                        <div class="flex items-center justify-between">
                            @if($surahNumber > 1)
                                <a href="{{ route('quran-readings.read', ['surah' => $surahNumber - 1]) }}" 
                                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    <span>Previous Surah</span>
                                </a>
                            @else
                                <div></div>
                            @endif

                            @if($surahNumber < 114)
                                <a href="{{ route('quran-readings.read', ['surah' => $surahNumber + 1]) }}" 
                                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center space-x-2">
                                    <span>Next Surah</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Track Reading Modal -->
    <div id="trackModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Track Reading</h3>
                    <button onclick="closeTrackModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('quran-readings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="surah_number" id="track_surah" value="{{ $surahNumber }}">
                    <input type="hidden" name="from_ayah" id="track_from">
                    <input type="hidden" name="to_ayah" id="track_to">
                    <input type="hidden" name="reading_date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">

                    <div class="mb-4">
                        <p class="text-gray-700 dark:text-gray-300 mb-2">
                            Surah: <span class="font-semibold">{{ $surahData['englishName'] ?? '' }}</span>
                        </p>
                        <p class="text-gray-700 dark:text-gray-300">
                            Ayah: <span id="track_range" class="font-semibold"></span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (Optional)</label>
                        <input type="time" name="duration" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white" placeholder="Refleksi atau catatan..."></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeTrackModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
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

    <style>
        /* Import Arabic font */
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
        
        .ayah-text {
            font-size: 2rem;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        let currentFontSize = 2; // rem

        function changeFontSize(delta) {
            currentFontSize += delta / 16; // Convert px to rem
            if (currentFontSize < 1.5) currentFontSize = 1.5;
            if (currentFontSize > 4) currentFontSize = 4;
            
            const ayahTexts = document.querySelectorAll('.ayah-text');
            ayahTexts.forEach(text => {
                text.style.fontSize = currentFontSize + 'rem';
            });
        }

        function trackFullSurah() {
            document.getElementById('track_from').value = 1;
            document.getElementById('track_to').value = {{ $surahData['numberOfAyahs'] ?? 1 }};
            document.getElementById('track_range').textContent = '1 - {{ $surahData['numberOfAyahs'] ?? 1 }}';
            document.getElementById('trackModal').classList.remove('hidden');
        }

        function trackSingleAyah(ayahNumber) {
            document.getElementById('track_from').value = ayahNumber;
            document.getElementById('track_to').value = ayahNumber;
            document.getElementById('track_range').textContent = ayahNumber;
            document.getElementById('trackModal').classList.remove('hidden');
        }

        function closeTrackModal() {
            document.getElementById('trackModal').classList.add('hidden');
        }

        // Scroll to specific ayah if hash is present
        if (window.location.hash) {
            setTimeout(() => {
                const element = document.querySelector(window.location.hash);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    element.classList.add('ring-2', 'ring-green-500');
                }
            }, 500);
        }
    </script>
</x-app-layout>
