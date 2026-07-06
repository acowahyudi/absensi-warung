<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dashboard Admin — Sistem Absensi GANJ'S">
    <title>@yield('title', 'Dashboard') — GANJ'S</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-ganjs-bg" x-data="{ sidebarOpen: true }">

    {{-- Toast --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition class="toast-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Sidebar --}}
    <aside class="sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <x-application-logo class="w-9 h-9 rounded-xl object-cover flex-shrink-0 bg-white" />
            <div>
                <p class="text-white font-bold text-sm leading-tight">GANJ'S</p>
                <p class="text-white/50 text-xs">Panel Admin</p>
            </div>
        </div>

        {{-- Nav items --}}
        <nav class="flex-1 overflow-y-auto py-4 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.karyawan.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Karyawan
            </a>

            <a href="{{ route('admin.shift.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.shift.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Shift
            </a>

            <a href="{{ route('admin.jadwal.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal
            </a>

            <a href="{{ route('admin.laporan.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Gaji
            </a>

            <a href="{{ route('admin.lokasi.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.lokasi.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Lokasi Kantor
            </a>
        </nav>

        {{-- User + logout --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-ganjs-primary flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-white/50 text-xs">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left sidebar-item text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay for mobile sidebar --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="lg:hidden fixed inset-0 z-20 bg-black/40 backdrop-blur-sm"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"></div>

    {{-- Main content --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">
        {{-- Topbar --}}
        <header class="bg-white border-b border-ganjs-border sticky top-0 z-10">
            <div class="flex items-center gap-4 px-6 py-4">
                {{-- Hamburger (mobile) --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-lg hover:bg-ganjs-bg transition-colors"
                        aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="text-lg font-bold text-ganjs-ink font-display">@yield('title', 'Dashboard')</h1>

                <div class="ml-auto flex items-center gap-3">
                    {{-- Current date --}}
                    <span class="hidden sm:block text-sm text-ganjs-ink-muted">
                        {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @livewireScripts
</body>
</html>
