@props(['title' => null, 'slot'])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#C1440E">
    <title>{{ $title ?? 'Absensi' }} — GANJ'S</title>
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-ganjs-bg overflow-x-hidden text-ganjs-ink antialiased">

    {{-- Banner PWA Install --}}
    <div x-data="pwaInstall()" x-show="showBanner" class="fixed top-6 left-4 right-4 z-50 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-ganjs-primary/20 animate-slide-up" style="display: none;">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-ganjs-ink">Pasang Aplikasi Absensi</p>
                <p class="text-xs text-ganjs-ink-muted mt-0.5">Instal di layar utama HP Anda untuk akses lebih cepat & stabil.</p>
                
                {{-- Android/Chrome Button --}}
                <div class="flex gap-2 mt-3" x-show="platform === 'android'">
                    <button @click="installApp()" class="btn-primary py-1.5 px-4 text-xs font-bold shadow-none">Pasang</button>
                    <button @click="dismissBanner()" class="btn-secondary py-1.5 px-4 text-xs font-semibold shadow-none border-0 hover:bg-ganjs-bg">Nanti</button>
                </div>

                {{-- iOS Safari Instruction --}}
                <div class="mt-3 text-xs text-ganjs-ink-muted flex items-start gap-1" x-show="platform === 'ios'">
                    <div class="flex-1">
                        <span>Ketuk tombol <b class="text-ganjs-primary">Bagikan (Share)</b> pada browser, lalu pilih <b class="text-ganjs-primary">Tambah ke Layar Utama (Add to Home Screen)</b>.</span>
                    </div>
                    <button @click="dismissBanner()" class="text-ganjs-primary font-bold ml-2 px-2 py-1">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast alerts (Premium floating banner) --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="toast-premium-success">
            <div class="w-8 h-8 rounded-lg bg-ganjs-secondary/15 text-ganjs-secondary flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-ganjs-ink">Berhasil</p>
                <p class="text-xs text-ganjs-ink-muted mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-ganjs-ink-muted/40 hover:text-ganjs-ink ml-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="toast-premium-error">
            <div class="w-8 h-8 rounded-lg bg-ganjs-danger/15 text-ganjs-danger flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-ganjs-ink">Gagal</p>
                <p class="text-xs text-ganjs-ink-muted mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-ganjs-ink-muted/40 hover:text-ganjs-ink ml-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="pb-28 min-h-screen">
        {{ $slot }}
    </main>

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav shadow-[0_-8px_24px_-4px_rgba(35,31,26,0.04)] border-t border-ganjs-border/50 py-2.5 bg-white/95 backdrop-blur-md">
        {{-- Absen --}}
        <a href="{{ route('karyawan.dashboard') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
            @if(request()->routeIs('karyawan.dashboard'))
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-ganjs-primary transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.736-5.23Z" clip-rule="evenodd" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-ganjs-ink-muted/80 transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            @endif
            <span class="mt-1">Absen</span>
        </a>

        {{-- Riwayat --}}
        <a href="{{ route('karyawan.riwayat') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.riwayat') ? 'active' : '' }}">
            @if(request()->routeIs('karyawan.riwayat'))
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-ganjs-primary transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-ganjs-ink-muted/80 transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            @endif
            <span class="mt-1">Riwayat</span>
        </a>

        {{-- Uang Makan --}}
        <a href="{{ route('karyawan.uang-makan') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.uang-makan') ? 'active' : '' }}">
            @if(request()->routeIs('karyawan.uang-makan'))
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-ganjs-primary transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path d="M12 7.5a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" />
                    <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM18 13.5a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z" clip-rule="evenodd" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-ganjs-ink-muted/80 transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18-3a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                </svg>
            @endif
            <span class="mt-1">Uang Makan</span>
        </a>

        {{-- Profil --}}
        <a href="{{ route('karyawan.profil') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.profil') ? 'active' : '' }}">
            @if(request()->routeIs('karyawan.profil'))
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-ganjs-primary transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12c0 2.76.135 3.907 1.025 5.057a7.5 7.5 0 1 1 15.41 2.04Z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M12 12.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-ganjs-ink-muted/80 transition-transform duration-200 hover:scale-105 active:scale-95">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            @endif
            <span class="mt-1">Profil</span>
        </a>
    </nav>

    @livewireScripts

    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }

        // PWA Install Prompt Script
        function pwaInstall() {
            return {
                showBanner: false,
                platform: 'other',
                deferredPrompt: null,

                init() {
                    const isStandalone = window.matchMedia('(display-mode: standalone)').matches 
                        || window.navigator.standalone 
                        || document.referrer.includes('android-app://');
                    
                    if (isStandalone) {
                        return;
                    }

                    const userAgent = window.navigator.userAgent.toLowerCase();
                    if (/iphone|ipad|ipod/.test(userAgent)) {
                        this.platform = 'ios';
                        if (!localStorage.getItem('pwa_dismissed')) {
                            this.showBanner = true;
                        }
                    } else if (/android/.test(userAgent)) {
                        this.platform = 'android';
                        
                        window.addEventListener('beforeinstallprompt', (e) => {
                            e.preventDefault();
                            this.deferredPrompt = e;
                            if (!localStorage.getItem('pwa_dismissed')) {
                                this.showBanner = true;
                            }
                        });
                    }
                },

                installApp() {
                    if (!this.deferredPrompt) return;
                    this.showBanner = false;
                    this.deferredPrompt.prompt();
                    this.deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('App installed successfully');
                        }
                        this.deferredPrompt = null;
                    });
                },

                dismissBanner() {
                    this.showBanner = false;
                    localStorage.setItem('pwa_dismissed', 'true');
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

