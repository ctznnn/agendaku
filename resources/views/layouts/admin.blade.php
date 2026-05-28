<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Sistem Agenda</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { 'inter': ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        * { transition: background-color 0.2s ease, border-color 0.2s ease; }
        .sidebar-icon { width: 2rem; text-align: center; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-inter antialiased">

    <!-- Top Navbar Admin -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-semibold text-gray-800 dark:text-white">Admin Panel</span>
                </div>

                <!-- Right Menu -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-moon dark:hidden text-lg"></i>
                        <i class="fas fa-sun hidden dark:inline text-lg"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user w-4"></i> Profil
                            </a>
                            <a href="{{ route('profile.password') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-key w-4"></i> Ganti Password
                            </a>
                            <hr class="my-1 border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-3">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Content -->
    <div class="flex">
        <!-- Sidebar Admin - Icon Only -->
        <aside class="w-20 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
            <div class="py-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.agendas.index') }}"
                           class="flex flex-col items-center justify-center py-3 px-2 mx-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition group {{ request()->routeIs('admin.agendas.index') ? 'bg-gray-100 dark:bg-gray-700 text-emerald-600' : 'text-gray-500 dark:text-gray-400' }}">
                            <i class="fas fa-tachometer-alt text-xl mb-1"></i>
                            <span class="text-xs">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.agendas.index') }}"
                           class="flex flex-col items-center justify-center py-3 px-2 mx-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition group text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-alt text-xl mb-1"></i>
                            <span class="text-xs">Agenda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.agendas.create') }}"
                           class="flex flex-col items-center justify-center py-3 px-2 mx-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition group text-gray-500 dark:text-gray-400">
                            <i class="fas fa-plus-circle text-xl mb-1"></i>
                            <span class="text-xs">Tambah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pegawai.index') }}"
                           class="flex flex-col items-center justify-center py-3 px-2 mx-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition group {{ request()->routeIs('admin.pegawai.*') ? 'bg-gray-100 dark:bg-gray-700 text-emerald-600' : 'text-gray-500 dark:text-gray-400' }}">
                            <i class="fas fa-users text-xl mb-1"></i>
                            <span class="text-xs">Pegawai</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script>
        const darkToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        if (localStorage.getItem('darkMode') === 'enabled') html.classList.add('dark');
        darkToggle?.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'disabled');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'enabled');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
