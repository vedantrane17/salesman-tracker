@extends('layouts.admin')

@section('title', 'Tracking Summary of ' . $user->name)

@section('content')
    <div class="bg-white rounded shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
                <div class="text-sm">Total Sessions</div>
                <div class="text-2xl font-bold">{{ count($sessions) }}</div>
            </div>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                <div class="text-sm">Total Duration</div>
                <div class="text-2xl font-bold">
                    {{ $sessions->sum('duration_minutes') }} mins
                </div>
            </div>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow">
                <div class="text-sm">Total Distance</div>
                <div class="text-2xl font-bold">
                    {{ number_format($sessions->sum('distance_km'), 2) }} km
                </div>
            </div>
        </div>

        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Start Time</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">End Time</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Total Duration</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Total Distance (km)</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $session)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $session['date'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $session['start_time'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $session['end_time'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $session['duration'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $session['distance_km'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($session['status'] === 'active')
                            <a href="{{ route('view-live', $session['id']) }}" class="text-green-600 font-semibold hover:underline">
                                Active
                            </a>
                        @else
                            <span class="text-red-500 font-semibold">Inactive</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">No sessions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
