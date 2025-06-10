<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true }" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - Admin Panel</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside 
        class="bg-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'w-64' : 'w-16'"
    >
        <div class="p-6 text-2xl font-bold border-b border-gray-800" x-show="sidebarOpen">Admin</div>
        <nav class="flex-1 p-6 space-y-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="block py-2 px-4 rounded hover:bg-gray-700 @if(request()->routeIs('admin.dashboard')) bg-gray-700 @endif">
                <span x-show="sidebarOpen">Dashboard</span>
                <span x-show="!sidebarOpen" class="text-xl">🏠</span>
            </a>
            <a href="{{ route('admin.users') }}" 
               class="block py-2 px-4 rounded hover:bg-gray-700 @if(request()->routeIs('admin.users')) bg-gray-700 @endif">
                <span x-show="sidebarOpen">All Users</span>
                <span x-show="!sidebarOpen" class="text-xl">👥</span>
            </a>
            <!-- Add more links here -->
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow p-4 flex items-center justify-between">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="p-2 rounded bg-gray-200 hover:bg-gray-300 focus:outline-none">
                ☰
            </button>
            <h1 class="text-xl font-semibold">@yield('title')</h1>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-auto p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
