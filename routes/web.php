<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);

// Route::get('/admin/live-map/{userId}', [AdminController::class, 'showLiveMap']);
// // Route::get('/admin/latest-location/{userId}', [AdminController::class, 'latestLocation']);
// Route::get('/admin/live-tracker-map/{userId}', [AdminController::class, 'showMap']);

Route::get('/admin/location-path/{userId}', function ($userId) {
    $locations = DB::table('locations')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'asc')
        ->get(['latitude', 'longitude']);

    return response()->json($locations);
});

Route::get('/admin/live-path/{userId}', function ($userId) {
    // Get latest session for the user
    $session = DB::table('tracking_sessions')
        ->where('user_id', $userId)
        ->orderByDesc('created_at')
        ->first();

    if (!$session) {
        return response()->json(['message' => 'No session found'], 404);
    }

    // Fetch location points for that session
    $locations = DB::table('locations')
        ->where('user_id', $userId)
        ->where('tracking_session_id', $session->id)
        ->orderBy('created_at', 'asc')
        ->get(['latitude', 'longitude']);

    return response()->json([
        'session_id' => $session->id,
        'locations' => $locations
    ]);
});

Route::get('/admin/live-map/{userId}', function ($userId) {
    return view('admin.tracker-map', compact('userId'));
});


Route::get('/admin/tracking-info/{userId}', [AdminController::class, 'trackingInfo']);
Route::get('/admin/api/session-data/{userId}', [AdminController::class, 'getSessionData']);

