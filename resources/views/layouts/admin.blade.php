<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - Admin Panel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-gray-800">Admin Panel</div>
        <nav class="flex-1 p-6">
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block py-2 px-4 rounded hover:bg-gray-700 @if(request()->routeIs('admin.dashboard')) bg-gray-700 @endif">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" 
                       class="block py-2 px-4 rounded hover:bg-gray-700 @if(request()->routeIs('admin.users')) bg-gray-700 @endif">
                        All Users
                    </a>
                </li>
                
                <!-- Add more links here -->
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow p-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold">@yield('title')</h1>
            <!-- You can add user profile, logout button etc here -->
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-auto p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
