<?php

namespace App\Http\Controllers\API;

use App\Models\LocationLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\LocationUpdated;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tracking_session_id' => 'required|exists:tracking_sessions,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'logged_at' => 'required|date',
        ]);

        $locationLog = LocationLog::create([
            'tracking_session_id' => $request->tracking_session_id,
            'user_id' => $request->user()->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'logged_at' => $request->logged_at,
        ]);

        broadcast(new LocationUpdated(
            $request->user()->id,
            $request->latitude,
            $request->longitude,
            $request->logged_at
        ))->toOthers();

        return response()->json(['message' => 'Location logged & broadcasted successfully', 'data' => $locationLog], 201);
    }
}
