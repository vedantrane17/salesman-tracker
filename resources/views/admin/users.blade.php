@extends('layouts.admin')

@section('title', 'All Users')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Created At</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('admin.user.tracking.summary', $user->id) }}"
                        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 transition">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
