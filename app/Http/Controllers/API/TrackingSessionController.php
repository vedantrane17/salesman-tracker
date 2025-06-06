<?php

namespace App\Http\Controllers\API;

use App\Models\TrackingSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrackingSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|in:start,stop',
            'tracking_session_id' => 'nullable|exists:tracking_sessions,id',
        ]);

        if ($request->action === 'start') {
            // Create new session
            $session = TrackingSession::create([
                'user_id' => $request->user()->id,
                'start_time' => now(),
                'active' => true,
                'total_distance' => 0,
            ]);

            return response()->json(['message' => 'Tracking started', 'tracking_session' => $session], 201);
        }

        if ($request->action === 'stop') {
            $session = TrackingSession::where('id', $request->tracking_session_id)
                ->where('user_id', $request->user()->id)
                ->where('active', true)
                ->first();

            if (!$session) {
                return response()->json(['message' => 'Active tracking session not found'], 404);
            }

            $session->end_time = now();
            $session->active = false;
            $session->save();

            return response()->json(['message' => 'Tracking stopped', 'tracking_session' => $session]);
        }
    }
}
