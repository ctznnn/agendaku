<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Aplikasi Agenda') - My Laravel App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles untuk class yang tidak tersedia di Tailwind default */
        .text-heading { color: #111827; }
        .dark .dark\:text-heading { color: #f3f4f6; }

        .bg-neutral-primary-soft { background-color: #f9fafb; }
        .dark .dark\:bg-neutral-primary-soft { background-color: #1f2937; }

        .border-default { border-color: #e5e7eb; }
        .dark .dark\:border-default { border-color: #374151; }

        .text-body { color: #374151; }
        .dark .dark\:text-body { color: #d1d5db; }

        .hover\:text-fg-brand:hover { color: #2563eb; }
        .dark .dark\:hover\:text-fg-brand:hover { color: #60a5fa; }

        .bg-neutral-secondary-soft { background-color: #f3f4f6; }
        .dark .dark\:bg-neutral-secondary-soft { background-color: #374151; }

        .text-fg-disabled { color: #9ca3af; }
        .dark .dark\:text-fg-disabled { color: #6b7280; }

        .border-default-medium { border-color: #d1d5db; }
        .dark .dark\:border-default-medium { border-color: #4b5563; }

        .text-fg-danger-strong { color: #dc2626; }
        .dark .dark\:text-fg-danger-strong { color: #f87171; }

        .bg-danger-soft { background-color: #fee2e2; }
        .dark .dark\:bg-danger-soft { background-color: #7f1d1d; }

        .border-danger-subtle { border-color: #fecaca; }
        .dark .dark\:border-danger-subtle { border-color: #b91c1c; }

        .rounded-base { border-radius: 0.375rem; }
        .bg-neutral-tertiary { background-color: #e5e7eb; }
        .dark .dark\:bg-neutral-tertiary { background-color: #4b5563; }

        .w-4\.5 { width: 1.125rem; }
        .h-4\.5 { height: 1.125rem; }

        /* Transisi untuk smooth dark mode */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <!-- Mobile Menu Button -->
    <button data-drawer-target="sidebar-multi-level-sidebar"
            data-drawer-toggle="sidebar-multi-level-sidebar"
            aria-controls="sidebar-multi-level-sidebar"
            type="button"
            class="text-gray-900 dark:text-gray-100 bg-transparent border border-transparent hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 font-medium rounded-lg ms-3 mt-3 text-sm p-2 focus:outline-none inline-flex sm:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
            <!-- Dark Mode Toggle di Sidebar -->
            <div class="flex items-center justify-between mb-4 px-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dark Mode</span>
                <button id="darkModeToggle" class="relative inline-flex items-center h-6 rounded-full w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 bg-gray-200 dark:bg-gray-600">
                    <span class="sr-only">Toggle dark mode</span>
                    <span class="inline-block w-4 h-4 transform transition-transform bg-white rounded-full shadow-xl translate-x-1 dark:translate-x-6"></span>
                </button>
            </div>

            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('agendas.index') }}" class="flex items-center px-2 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group @if(request()->routeIs('agendas.index')) bg-gray-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400 @endif">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-blue-600 dark:group-hover:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Agenda -->
                <li>
                    <a href="{{ route('agendas.index') }}" class="flex items-center px-2 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-blue-600 dark:group-hover:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Agenda</span>
                    </a>
                </li>

                <!-- Tambah Agenda -->
                <li>
                    <a href="{{ route('agendas.create') }}" class="flex items-center px-2 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-blue-600 dark:group-hover:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="ms-3">Tambah Agenda</span>
                    </a>
                </li>

                <!-- Inbox -->
                <li>
                    <a href="#" class="flex items-center px-2 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-blue-600 dark:group-hover:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13h3.439a.991.991 0 0 1 .908.6 3.978 3.978 0 0 0 7.306 0 .99.99 0 0 1 .908-.6H20M4 13v6a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-6M4 13l2-9h12l2 9M9 7h6m-7 3h8"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Inbox</span>
                        <span class="inline-flex items-center justify-center w-5 h-5 ms-2 text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-800 rounded-full">2</span>
                    </a>
                </li>

                <!-- Users -->
                <li>
                    <a href="#" class="flex items-center px-2 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-blue-600 dark:group-hover:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                    </a>
                </li>

                <!-- Sign In -->
                <li class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="#" class="flex items-center px-2 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-blue-600 dark:group-hover:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 border-dashed rounded-lg bg-white dark:bg-gray-800 min-h-screen">
            @yield('content')
        </div>
    </div>

    <!-- Dark Mode Toggle Script -->
    <script>
        // Dark mode toggle functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;

        // Cek preferensi dark mode dari localStorage
        if (localStorage.getItem('darkMode') === 'enabled') {
            htmlElement.classList.add('dark');
            if (darkModeToggle) {
                const span = darkModeToggle.querySelector('span:last-child');
                if (span) span.classList.remove('translate-x-1');
                if (span) span.classList.add('translate-x-6');
            }
        } else {
            htmlElement.classList.remove('dark');
            if (darkModeToggle) {
                const span = darkModeToggle.querySelector('span:last-child');
                if (span) span.classList.add('translate-x-1');
                if (span) span.classList.remove('translate-x-6');
            }
        }

        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                const span = darkModeToggle.querySelector('span:last-child');
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.setItem('darkMode', 'disabled');
                    if (span) {
                        span.classList.add('translate-x-1');
                        span.classList.remove('translate-x-6');
                    }
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'enabled');
                    if (span) {
                        span.classList.remove('translate-x-1');
                        span.classList.add('translate-x-6');
                    }
                }
            });
        }

        // Mobile menu toggle
        document.querySelector('[data-drawer-toggle]')?.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar-multi-level-sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            const sidebar = document.getElementById('sidebar-multi-level-sidebar');
            const menuButton = document.querySelector('[data-drawer-toggle]');

            if (sidebar && menuButton) {
                if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
                    if (window.innerWidth < 640) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
