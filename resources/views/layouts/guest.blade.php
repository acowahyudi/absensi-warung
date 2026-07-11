<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Absensi') }} — GANJ'S</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ganjs-ink antialiased h-full bg-gradient-to-br from-[#FBF7F2] via-[#F5EEE3] to-[#EADBC8] overflow-hidden relative">
        
        <!-- Animated Background Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-ganjs-primary/8 blur-[120px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full bg-ganjs-secondary/10 blur-[130px] animate-blob animation-delay-2000"></div>
            <div class="absolute top-[30%] right-[15%] w-[40%] h-[40%] rounded-full bg-ganjs-warning/6 blur-[100px] animate-blob animation-delay-4000"></div>
        </div>

        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 relative z-10">
            <!-- Glassmorphic Form Card -->
            <div class="w-full sm:max-w-md glass-card p-6 sm:p-10 rounded-2xl transition-all duration-300 hover:shadow-[0_16px_48px_-12px_rgba(193,68,14,0.12)]">
                <!-- Branding Header -->
                <div class="flex flex-col items-center mb-8">
                    <a href="/" wire:navigate class="transition-transform duration-300 hover:scale-105">
                        <x-application-logo class="w-20 h-20 object-contain drop-shadow-md" />
                    </a>
                    <h2 class="text-xl font-bold font-display text-ganjs-ink mt-4 text-center">
                        Ayam Bebek GANJ'S
                    </h2>
                    <p class="text-xs text-ganjs-ink-muted mt-1 text-center font-medium">
                        Sistem Absensi Karyawan Cak Ali
                    </p>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>

