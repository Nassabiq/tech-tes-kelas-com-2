<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchTime;
use App\Models\Subscription;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;

class WatchTimeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'duration' => 'required|integer'
        ]);

        WatchTime::create($request->all());

        return response()->json(['message' => 'Watch time recorded successfully'], 200);
    }

    public function revenueShare()
    {
        $totalRevenue = 69000;
        $expenses = $totalRevenue * 0.20;
        $netRevenue = $totalRevenue - $expenses;

        $totalWatchTime = WatchTime::sum('duration');
        $classWatchTimes = WatchTime::select('class_id', DB::raw('SUM(duration) as total_duration'))
                            ->groupBy('class_id')
                            ->get();

        $revenueShares = $classWatchTimes->map(function($item) use ($netRevenue, $totalWatchTime) {
            $class = Classes::find($item->class_id);
            $mentor = $class->mentor;
            $percentage = $item->total_duration / $totalWatchTime;
            $share = $netRevenue * $percentage;

            return [
                'mentor' => $mentor->name,
                'class' => $class->title,
                'watch_time' => $item->total_duration,
                'percentage' => $percentage,
                'share' => $share
            ];
        });

        return response()->json($revenueShares, 200);
    }
}
