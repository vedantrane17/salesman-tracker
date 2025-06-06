<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\TrackingSessionController;
use App\Http\Controllers\AdminController;
use Carbon\Carbon;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin
    Route::get('/user-summary', [AdminController::class, 'userSummary'])->middleware('can:isAdmin');

    // Tracking session start
    Route::post('/start-tracking', function (Request $request) {
        $userId = $request->user()->id;

        $sessionId = DB::table('tracking_sessions')->insertGetId([
            'user_id' => $userId,
            'start_time' => now(),
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'tracking_session_id' => $sessionId,
        ]);
    });

    // Tracking session end
    Route::post('/end-tracking', function (Request $request) {
        $request->validate([
            'tracking_session_id' => 'required|exists:tracking_sessions,id',
        ]);

        DB::table('tracking_sessions')
            ->where('id', $request->tracking_session_id)
            ->update([
                'end_time' => Carbon::now('UTC'),
                'active' => false,
                'updated_at' => Carbon::now('UTC'),
            ]);

        return response()->json(['status' => 'tracking session ended']);
    });

    // Update location
    Route::post('/update-location', function (Request $request) {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'tracking_session_id' => 'required|exists:tracking_sessions,id',
        ]);

        DB::table('locations')->insert([
            'user_id' => $request->user()->id,
            'tracking_session_id' => $request->tracking_session_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
    });
});

// Auth user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Broadcast auth (optional for future)
Route::post('/broadcasting/auth', function (Request $request) {
    if (! $request->user()) {
        return response()->json(['message' => 'Unauthenticated.'], 403);
    }
    return Broadcast::auth($request);
})->middleware('auth:sanctum');

// Health check
Route::get('/test', function () {
    return response()->json(['status' => 'ok']);
});
