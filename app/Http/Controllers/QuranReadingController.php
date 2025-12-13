<?php

namespace App\Http\Controllers;

use App\Models\QuranReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QuranReadingController extends Controller
{
    /**
     * Display Quran surah reader
     */
    public function read(Request $request)
    {
        $surahNumber = $request->input('surah', 1);
        
        try {
            // Get surah data from API
            $response = Http::get("https://api.alquran.cloud/v1/surah/{$surahNumber}/id.indonesian");
            
            if ($response->successful()) {
                $surahData = $response->json()['data'];
                
                // Get Arabic text
                $responseArabic = Http::get("https://api.alquran.cloud/v1/surah/{$surahNumber}");
                $arabicData = $responseArabic->successful() ? $responseArabic->json()['data'] : null;
                
                return view('quran.read', compact('surahData', 'arabicData', 'surahNumber'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load Quran data. Please try again.');
        }
        
        return redirect()->back()->with('error', 'Surah not found.');
    }

    /**
     * Display Quran reading dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get today's readings
        $todayReadings = QuranReading::where('user_id', $user->id)
            ->whereDate('reading_date', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics for current month
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        
        $monthlyStats = [
            'total_readings' => QuranReading::where('user_id', $user->id)
                ->whereBetween('reading_date', [$monthStart, $monthEnd])
                ->count(),
            'total_ayahs' => QuranReading::where('user_id', $user->id)
                ->whereBetween('reading_date', [$monthStart, $monthEnd])
                ->sum('total_ayahs_read'),
            'unique_surahs' => QuranReading::where('user_id', $user->id)
                ->whereBetween('reading_date', [$monthStart, $monthEnd])
                ->distinct('surah_number')
                ->count('surah_number'),
            'days_read' => QuranReading::where('user_id', $user->id)
                ->whereBetween('reading_date', [$monthStart, $monthEnd])
                ->distinct('reading_date')
                ->count('reading_date'),
        ];
        
        // Get recent readings (last 7 days)
        $recentReadings = QuranReading::where('user_id', $user->id)
            ->whereBetween('reading_date', [Carbon::now()->subDays(7), $today])
            ->orderBy('reading_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($reading) {
                return $reading->reading_date->format('Y-m-d');
            });
        
        // Get overall progress
        $totalAyahsRead = QuranReading::where('user_id', $user->id)->sum('total_ayahs_read');
        $totalAyahsInQuran = 6236; // Total ayahs in Quran
        $progressPercentage = $totalAyahsInQuran > 0 ? round(($totalAyahsRead / $totalAyahsInQuran) * 100, 2) : 0;
        
        // Get reading streak
        $streak = $this->calculateStreak($user->id);
        
        // Get list of all surahs for selection
        $surahs = QuranReading::SURAHS;
        
        return view('quran.index', compact(
            'todayReadings',
            'monthlyStats',
            'recentReadings',
            'totalAyahsRead',
            'progressPercentage',
            'streak',
            'surahs'
        ));
    }

    /**
     * Store a new reading
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surah_number' => 'required|integer|min:1|max:114',
            'from_ayah' => 'required|integer|min:1',
            'to_ayah' => 'required|integer|min:1',
            'reading_date' => 'required|date',
            'duration' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Calculate total ayahs read
        $totalAyahs = ($validated['to_ayah'] - $validated['from_ayah']) + 1;
        
        QuranReading::create([
            'user_id' => Auth::id(),
            'surah_number' => $validated['surah_number'],
            'from_ayah' => $validated['from_ayah'],
            'to_ayah' => $validated['to_ayah'],
            'total_ayahs_read' => $totalAyahs,
            'reading_date' => $validated['reading_date'],
            'duration' => $validated['duration'],
            'notes' => $validated['notes'],
        ]);
        
        return redirect()->back()->with('success', 'Quran reading tracked successfully!');
    }

    /**
     * Update reading
     */
    public function update(Request $request, QuranReading $quranReading)
    {
        if ($quranReading->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'surah_number' => 'required|integer|min:1|max:114',
            'from_ayah' => 'required|integer|min:1',
            'to_ayah' => 'required|integer|min:1',
            'duration' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Calculate total ayahs read
        $totalAyahs = ($validated['to_ayah'] - $validated['from_ayah']) + 1;
        $validated['total_ayahs_read'] = $totalAyahs;
        
        $quranReading->update($validated);
        
        return redirect()->back()->with('success', 'Reading updated successfully!');
    }

    /**
     * Delete reading
     */
    public function destroy(QuranReading $quranReading)
    {
        if ($quranReading->user_id !== Auth::id()) {
            abort(403);
        }
        
        $quranReading->delete();
        
        return redirect()->back()->with('success', 'Reading deleted!');
    }

    /**
     * Get statistics for date range
     */
    public function statistics(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        $readings = QuranReading::where('user_id', Auth::id())
            ->whereBetween('reading_date', [$startDate, $endDate])
            ->orderBy('reading_date', 'desc')
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_readings' => $readings->count(),
            'total_ayahs' => $readings->sum('total_ayahs_read'),
            'unique_surahs' => $readings->unique('surah_number')->count(),
            'total_days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1,
            'days_read' => $readings->unique('reading_date')->count(),
            'average_ayahs_per_day' => $readings->unique('reading_date')->count() > 0 
                ? round($readings->sum('total_ayahs_read') / $readings->unique('reading_date')->count(), 2)
                : 0,
            'most_read_surah' => $this->getMostReadSurah($readings),
        ];
        
        // Get Juz progress (30 Juz in Quran)
        $juzProgress = $this->calculateJuzProgress(Auth::id());
        
        return view('quran.statistics', compact('stats', 'readings', 'startDate', 'endDate', 'juzProgress'));
    }

    /**
     * Calculate reading streak
     */
    private function calculateStreak($userId)
    {
        $streak = 0;
        $currentDate = Carbon::today();
        
        while (true) {
            $hasReading = QuranReading::where('user_id', $userId)
                ->whereDate('reading_date', $currentDate)
                ->exists();
            
            if ($hasReading) {
                $streak++;
                $currentDate = $currentDate->subDay();
            } else {
                break;
            }
        }
        
        return $streak;
    }

    /**
     * Get most read surah
     */
    private function getMostReadSurah($readings)
    {
        if ($readings->isEmpty()) {
            return null;
        }
        
        $surahCounts = $readings->groupBy('surah_number')
            ->map(function($group) {
                return $group->count();
            })
            ->sortDesc();
        
        $mostReadSurahNumber = $surahCounts->keys()->first();
        
        return [
            'number' => $mostReadSurahNumber,
            'count' => $surahCounts->first(),
            'name' => QuranReading::SURAHS[$mostReadSurahNumber]['latin'] ?? '',
        ];
    }

    /**
     * Calculate Juz progress (simplified)
     */
    private function calculateJuzProgress($userId)
    {
        // This is a simplified version
        // You can enhance this with accurate Juz boundaries
        $totalAyahs = QuranReading::where('user_id', $userId)->sum('total_ayahs_read');
        $ayahsPerJuz = 6236 / 30; // Approximate
        
        return round($totalAyahs / $ayahsPerJuz, 2);
    }
}
