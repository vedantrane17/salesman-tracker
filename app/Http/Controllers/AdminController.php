<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLiveMap($userId)
    {
        return view('admin.live-map', compact('userId'));
    }

    public function latestLocation($userId)
    {
        $location = DB::table('locations')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        if ($location) {
            return response()->json([
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
            ]);
        } else {
            return response()->json(['error' => 'No location found'], 404);
        }
    }

    public function showMap($userId)
    {
        $locations = DB::table('locations')
            ->where('user_id', $userId)
            ->orderBy('created_at')
            ->get(['latitude', 'longitude']);

        return view('admin.tracker-map', compact('locations'));
    }

    public function trackingInfo($sessionId)
{
    return view('admin.tracking-info', compact('sessionId'));
}

public function getSessionData($sessionId)
{
    $session = DB::table('tracking_sessions')
        ->where('id', $sessionId)
        ->first();

    if (!$session) {
        return response()->json(['message' => 'No tracking session found.'], 404);
    }

    $locations = DB::table('locations')
        ->where('tracking_session_id', $session->id)
        ->orderBy('created_at')
        ->get();

    // Convert UTC to IST
    $start = Carbon::parse($session->created_at)->setTimezone('Asia/Kolkata');
    $end = $session->end_time ? Carbon::parse($session->end_time)->setTimezone('Asia/Kolkata') : null;
    // $session_id = $session->id;

    $distance = 0;
    for ($i = 1; $i < count($locations); $i++) {
        $distance += $this->haversineGreatCircleDistance(
            $locations[$i - 1]->latitude,
            $locations[$i - 1]->longitude,
            $locations[$i]->latitude,
            $locations[$i]->longitude
        );
    }

    return response()->json([
        'start_time' => $start->format('Y-m-d H:i:s'),
        'end_time' => $end ? $end->format('Y-m-d H:i:s') : null,
        'duration' => $end ? $start->diff($end)->format('%H:%I:%S') : 'Live',
        'distance_km' => round($distance, 2),
        'locations' => $locations,
        'id' => $session->id,
    ]);
}

private function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371)
{
    $earthRadius = 6371; // in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

public function userTrackingSummary($userId)
{
    $user = DB::table('users')->find($userId);

    $sessions = DB::table('tracking_sessions')
        ->where('user_id', $userId)
        ->orderByDesc('start_time')
        ->get()
        ->map(function ($session) {
            $locations = DB::table('locations')
                ->where('tracking_session_id', $session->id)
                ->orderBy('created_at')
                ->get();

            $distance = 0;
            for ($i = 1; $i < count($locations); $i++) {
                $distance += $this->haversineGreatCircleDistance_1(
                    $locations[$i - 1]->latitude,
                    $locations[$i - 1]->longitude,
                    $locations[$i]->latitude,
                    $locations[$i]->longitude
                );
            }

            $start = \Carbon\Carbon::parse($session->created_at)->setTimezone('Asia/Kolkata');
            $end = $session->end_time ? \Carbon\Carbon::parse($session->end_time)->setTimezone('Asia/Kolkata') : null;
            $durationMinutes = $start->diffInMinutes($end);

            return [
                'id' => $session->id,
                'date' => $start->format('Y-m-d'),
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end ? $end->format('H:i:s') : 'Tracking Ongoing',
                'duration' => $end ? $start->diff($end)->format('%H:%I:%S') : 'Live',
                'distance_km' => round($distance, 2),
                'duration_minutes' => $durationMinutes,
                'status' => $end ? 'inactive' : 'active',
            ];
        });

    return view('admin.user-tracking-summary', compact('user', 'sessions'));
}

private function haversineGreatCircleDistance_1($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371)
{
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}


}
