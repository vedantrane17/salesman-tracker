@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold mb-2">Total Users</h2>
            <p class="text-3xl font-bold text-blue-600">150</p>
        </div>
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold mb-2">Live Users</h2>
            <p class="text-3xl font-bold text-green-600">12</p>
        </div>
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold mb-2">Other Stats</h2>
            <p class="text-3xl font-bold text-purple-600">75</p>
        </div>
    </div>
@endsection
