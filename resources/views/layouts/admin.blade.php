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

    <!-- Heroicons CDN (outline) -->
    <script src="https://unpkg.com/@heroicons/vue@2/outline/index.umd.js"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside 
        class="bg-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'w-64' : 'w-16'"
    >
        <div class="p-6 text-2xl font-bold border-b border-gray-800" x-show="sidebarOpen">Admin</div>
        <nav class="flex-1 p-2 space-y-1">
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-gray-700 @if(request()->routeIs('admin.dashboard')) bg-gray-700 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4 0h4m4 0h4m1 0v-8l-7-7-7 7v8" />
                </svg>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>

            <!-- Users Link -->
            <a href="{{ route('admin.users') }}" 
               class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-gray-700 @if(request()->routeIs('admin.users')) bg-gray-700 @endif">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M17 20h5v-2a3 3 0 00-3-3h-2M9 20H4v-2a3 3 0 013-3h2m3-3a3 3 0 100-6 3 3 0 000 6zm6 0a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
                <span x-show="sidebarOpen">Users</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow p-4 flex items-center justify-between">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="p-2 rounded bg-gray-200 hover:bg-gray-300 focus:outline-none">
                <!-- Menu Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
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
