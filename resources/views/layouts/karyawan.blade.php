<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#C1440E">
    <meta name="description" content="Sistem Absensi Karyawan Ayam Bebek GANJ'S Cak Ali">
    <title>{{ $title ?? 'Absensi' }} — GANJ'S</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-ganjs-bg overflow-x-hidden">

    {{-- Banner PWA Install --}}
    <div x-data="pwaInstall()" x-show="showBanner" class="fixed top-4 left-4 right-4 z-50 bg-white p-4 rounded-xl shadow-lg border border-ganjs-primary/20 animate-slide-up" style="display: none;">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-ganjs-ink">Pasang Aplikasi Absensi</p>
                <p class="text-xs text-ganjs-ink-muted mt-0.5">Instal di HP Anda untuk akses lebih cepat dan stabil.</p>
                
                {{-- Android/Chrome Button --}}
                <div class="flex gap-2 mt-3" x-show="platform === 'android'">
                    <button @click="installApp()" class="btn-primary py-1.5 px-4 text-xs font-bold shadow-none">Pasang</button>
                    <button @click="dismissBanner()" class="btn-secondary py-1.5 px-4 text-xs font-semibold shadow-none border-0 hover:bg-ganjs-bg">Nanti</button>
                </div>

                {{-- iOS Safari Instruction --}}
                <div class="mt-3 text-xs text-ganjs-ink-muted flex items-start gap-1" x-show="platform === 'ios'">
                    <div class="flex-1">
                        <span>Ketuk tombol <b>Bagikan (Share)</b> pada browser, lalu pilih <b>Tambah ke Layar Utama (Add to Home Screen)</b>.</span>
                    </div>
                    <button @click="dismissBanner()" class="text-ganjs-primary font-bold ml-2 px-2 py-1">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast notification --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="toast-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="toast-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content --}}
    <main class="pb-24 min-h-screen">
        {{ $slot }}
    </main>

    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
        {{-- Absen --}}
        <a href="{{ route('karyawan.dashboard') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Absen</span>
        </a>

        {{-- Riwayat --}}
        <a href="{{ route('karyawan.riwayat') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.riwayat') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>Riwayat</span>
        </a>

        {{-- Uang Makan --}}
        <a href="{{ route('karyawan.uang-makan') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.uang-makan') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span>Uang Makan</span>
        </a>

        {{-- Profil --}}
        <a href="{{ route('karyawan.profil') }}"
           class="bottom-nav-item {{ request()->routeIs('karyawan.profil') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Profil</span>
        </a>
    </nav>

    @livewireScripts

    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.log('SW error:', err));
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
                        return; // Jangan tampilkan jika sudah terinstall
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
</body>
</html>
