<?php

namespace App\Http\Controllers;

use App\Models\Prayer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PrayerController extends Controller
{
    /**
     * Display prayer dashboard with statistics and today's prayers
     */
    public function index()
    {
        $today = Carbon::today();
        $user = Auth::user();
        
        // Get today's prayers
        $todayPrayers = Prayer::where('user_id', $user->id)
            ->whereDate('prayer_date', $today)
            ->get()
            ->keyBy('prayer_name');
        
        // Get prayer times from API
        $prayerTimes = $this->getPrayerTimes();
        
        // Calculate statistics for current month
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        
        $stats = [
            'total' => Prayer::where('user_id', $user->id)
                ->whereBetween('prayer_date', [$monthStart, $monthEnd])
                ->count(),
            'on_time' => Prayer::where('user_id', $user->id)
                ->whereBetween('prayer_date', [$monthStart, $monthEnd])
                ->where('status', 'on_time')
                ->count(),
            'qadha' => Prayer::where('user_id', $user->id)
                ->whereBetween('prayer_date', [$monthStart, $monthEnd])
                ->where('status', 'qadha')
                ->count(),
            'jamaah' => Prayer::where('user_id', $user->id)
                ->whereBetween('prayer_date', [$monthStart, $monthEnd])
                ->where('is_jamaah', true)
                ->count(),
        ];
        
        // Get recent prayer history (last 7 days)
        $recentPrayers = Prayer::where('user_id', $user->id)
            ->whereBetween('prayer_date', [Carbon::now()->subDays(7), $today])
            ->orderBy('prayer_date', 'desc')
            ->orderByRaw("FIELD(prayer_name, 'fajr', 'dhuhr', 'asr', 'maghrib', 'isha')")
            ->get()
            ->groupBy(function($prayer) {
                return $prayer->prayer_date->format('Y-m-d');
            });
        
        // Get missing prayers (last 7 days excluding today)
        $missingPrayers = $this->getMissingPrayers($user->id, 7);
        
        return view('prayers.index', compact('todayPrayers', 'prayerTimes', 'stats', 'recentPrayers', 'missingPrayers'));
    }

    /**
     * Store prayer tracking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prayer_name' => 'required|in:fajr,dhuhr,asr,maghrib,isha',
            'prayer_date' => 'required|date',
            'status' => 'required|in:on_time,qadha,missed',
            'is_jamaah' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['prayed_at'] = Carbon::now();
        $validated['is_jamaah'] = $request->has('is_jamaah');
        
        // Update or create prayer record
        Prayer::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'prayer_name' => $validated['prayer_name'],
                'prayer_date' => $validated['prayer_date'],
            ],
            $validated
        );
        
        return redirect()->back()->with('success', 'Prayer tracked successfully!');
    }

    /**
     * Update prayer tracking
     */
    public function update(Request $request, Prayer $prayer)
    {
        if ($prayer->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:on_time,qadha,missed',
            'is_jamaah' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $validated['is_jamaah'] = $request->has('is_jamaah');
        
        $prayer->update($validated);
        
        return redirect()->back()->with('success', 'Prayer updated successfully!');
    }

    /**
     * Delete prayer tracking
     */
    public function destroy(Prayer $prayer)
    {
        if ($prayer->user_id !== Auth::id()) {
            abort(403);
        }
        
        $prayer->delete();
        
        return redirect()->back()->with('success', 'Prayer record deleted!');
    }

    /**
     * Get prayer statistics for a date range
     */
    public function statistics(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        $prayers = Prayer::where('user_id', Auth::id())
            ->whereBetween('prayer_date', [$startDate, $endDate])
            ->orderBy('prayer_date', 'desc')
            ->get();
        
        $stats = [
            'total_days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1,
            'total_prayers' => $prayers->count(),
            'on_time' => $prayers->where('status', 'on_time')->count(),
            'qadha' => $prayers->where('status', 'qadha')->count(),
            'missed' => $prayers->where('status', 'missed')->count(),
            'jamaah' => $prayers->where('is_jamaah', true)->count(),
            'by_prayer' => [
                'fajr' => $prayers->where('prayer_name', 'fajr')->count(),
                'dhuhr' => $prayers->where('prayer_name', 'dhuhr')->count(),
                'asr' => $prayers->where('prayer_name', 'asr')->count(),
                'maghrib' => $prayers->where('prayer_name', 'maghrib')->count(),
                'isha' => $prayers->where('prayer_name', 'isha')->count(),
            ],
        ];
        
        return view('prayers.statistics', compact('stats', 'startDate', 'endDate', 'prayers'));
    }

    /**
     * Get missing prayers for the last N days (excluding today)
     */
    private function getMissingPrayers($userId, $days = 7)
    {
        $missingPrayers = [];
        $prayerNames = ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];
        
        // Check last N days excluding today
        for ($i = 1; $i <= $days; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Get tracked prayers for this date
            $trackedPrayers = Prayer::where('user_id', $userId)
                ->whereDate('prayer_date', $date)
                ->pluck('prayer_name')
                ->toArray();
            
            // Find missing prayers
            $missing = array_diff($prayerNames, $trackedPrayers);
            
            if (!empty($missing)) {
                $missingPrayers[$date->format('Y-m-d')] = [
                    'date' => $date,
                    'prayers' => $missing,
                ];
            }
        }
        
        return $missingPrayers;
    }
    
    /**
     * Get prayer times from API (using Aladhan API)
     */
    private function getPrayerTimes()
    {
        try {
            // Default location (Bandung, Indonesia)
            // You can make this configurable per user
            $city = 'Bandung';
            $country = 'Indonesia';
            $method = 11; // Kementerian Agama Indonesia
            
            $response = Http::get("http://api.aladhan.com/v1/timingsByCity", [
                'city' => $city,
                'country' => $country,
                'method' => $method,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'fajr' => $data['data']['timings']['Fajr'] ?? '04:30',
                    'dhuhr' => $data['data']['timings']['Dhuhr'] ?? '12:00',
                    'asr' => $data['data']['timings']['Asr'] ?? '15:15',
                    'maghrib' => $data['data']['timings']['Maghrib'] ?? '18:00',
                    'isha' => $data['data']['timings']['Isha'] ?? '19:15',
                    'location' => $city . ', ' . $country,
                ];
            }
        } catch (\Exception $e) {
            // Fallback to default times if API fails
        }
        
        // Default prayer times (Bandung)
        return [
            'fajr' => '04:30',
            'dhuhr' => '12:00',
            'asr' => '15:15',
            'maghrib' => '18:00',
            'isha' => '19:15',
            'location' => 'Bandung, Indonesia (Default)',
        ];
    }
}
