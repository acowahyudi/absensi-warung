<div x-data="absenMasuk()" class="flex flex-col items-center py-6">

    {{-- Tombol Absen Lingkaran Besar --}}
    <div class="relative mb-6" @click="handleClick">
        {{-- Concentric Scanner Ripples (Hanya saat idle) --}}
        <div x-show="state === 'idle'" class="absolute inset-0 z-0">
            <div class="scanner-ripple absolute inset-0"></div>
            <div class="scanner-ripple scanner-ripple-2 absolute inset-0"></div>
        </div>

        {{-- Tombol utama --}}
        <button
            :class="{
                'absen-btn-idle bg-gradient-to-br from-ganjs-primary via-[#D84F14] to-[#A33405] hover:scale-105 hover:shadow-[0_16px_36px_-6px_rgba(193,68,14,0.45)]': state === 'idle',
                'absen-btn-loading bg-gradient-to-br from-ganjs-warning via-[#EC9F05] to-[#B87A04] cursor-wait': state === 'loading',
                'absen-btn-success bg-gradient-to-br from-ganjs-secondary via-[#3B663F] to-[#1E3B22]': state === 'success' || state === 'sudah_absen',
                'absen-btn-error bg-gradient-to-br from-ganjs-danger via-[#C9352E] to-[#911812]': state === 'error' || state === 'no_jadwal',
            }"
            :disabled="state === 'loading' || state === 'success' || state === 'sudah_absen'"
            aria-label="Tombol Absen Masuk"
            class="w-48 h-48 rounded-full text-white font-bold text-lg relative z-10 transition-all duration-300 active:scale-95 disabled:scale-100 disabled:opacity-100 shadow-btn flex flex-col items-center justify-center gap-2 overflow-hidden group border-4 border-white/10">

            {{-- Laser Sweep Animation (Hanya saat loading/mencari lokasi) --}}
            <div x-show="state === 'loading'" class="scanner-sweep z-20"></div>

            {{-- Icon & Label berdasarkan state --}}
            {{-- Idle (Radar Map Pin Target) --}}
            <template x-if="state === 'idle'">
                <div class="flex flex-col items-center gap-2 z-10 mt-1">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <!-- Outer rotating dashed ring -->
                        <svg class="absolute inset-0 w-full h-full text-white/40 animate-[spin_8s_linear_infinite]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10" stroke-dasharray="3 3" />
                        </svg>
                        <!-- Center Location Pin Beacon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white transition-transform group-hover:scale-110 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-extrabold tracking-widest uppercase mt-1">TAP UNTUK ABSEN</span>
                </div>
            </template>

            {{-- Loading (Scanning Mode) --}}
            <template x-if="state === 'loading'">
                <div class="flex flex-col items-center gap-2.5 z-10">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <!-- Pulse beacon -->
                        <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-white/30 opacity-75"></span>
                        <svg class="animate-spin w-12 h-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-extrabold tracking-widest uppercase mt-0.5">MEMINDAI LOKASI</span>
                </div>
            </template>

            {{-- Success / Sudah Absen --}}
            <template x-if="state === 'success' || state === 'sudah_absen'">
                <div class="flex flex-col items-center gap-2 z-10 animate-[fade-in_0.3s_ease-out]">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/35 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-extrabold tracking-widest uppercase mt-1" x-text="waktuAbsen ? `Pukul ${waktuAbsen}` : 'BERHASIL'"></span>
                </div>
            </template>

            {{-- Error --}}
            <template x-if="state === 'error'">
                <div class="flex flex-col items-center gap-2 z-10 animate-[bounce_0.5s_ease-in-out]">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/35">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="text-xs font-extrabold tracking-widest uppercase mt-1">GAGAL</span>
                </div>
            </template>

            {{-- No jadwal --}}
            <template x-if="state === 'no_jadwal'">
                <div class="flex flex-col items-center gap-2 z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-extrabold tracking-widest uppercase mt-1">TIDAK ADA JADWAL</span>
                </div>
            </template>
        </button>
    </div>

    {{-- Status chip message --}}
    <div class="min-h-[2.75rem] flex items-center justify-center w-full px-6">
        <template x-if="pesan">
            <div x-transition
                 :class="{
                    'bg-ganjs-secondary-light/40 text-ganjs-secondary border border-ganjs-secondary/25': state === 'success' || state === 'sudah_absen',
                    'bg-ganjs-danger-light/45 text-ganjs-danger border border-ganjs-danger/25': state === 'error',
                    'bg-ganjs-warning-light/45 text-ganjs-warning border border-ganjs-warning/25': state === 'loading',
                    'bg-ganjs-border/30 text-ganjs-ink-muted border border-ganjs-border/20': state === 'no_jadwal',
                 }"
                 class="px-4 py-2.5 rounded-full text-xs font-bold text-center max-w-sm shadow-[0_4px_12px_rgba(0,0,0,0.02)] animate-slide-up">
                <span x-text="pesan"></span>
            </div>
        </template>

        <template x-if="!pesan && state === 'idle'">
            <p class="text-xs font-bold text-ganjs-ink-muted/80 text-center tracking-wide uppercase">Sentuh sensor untuk absen masuk</p>
        </template>
    </div>
</div>

<script>
function absenMasuk() {
    return {
        state: @js($state),
        pesan: @js($pesan),
        waktuAbsen: @js($waktuAbsen),

        handleClick() {
            if (this.state === 'success' || this.state === 'sudah_absen'
                || this.state === 'loading' || this.state === 'no_jadwal') return;

            this.state = 'loading';
            this.pesan = 'Menghubungkan GPS...';

            if (!navigator.geolocation) {
                this.state = 'error';
                this.pesan = 'Perangkat Anda tidak mendukung pemindaian GPS.';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    this.pesan = 'Memverifikasi koordinat lokasi...';

                    // Kirim ke Livewire untuk validasi server-side
                    @this.prosesAbsen(lat, lon);
                },
                (error) => {
                    this.state = 'error';
                    switch (error.code) {
                        case 1:
                            this.pesan = 'Akses lokasi diblokir. Izinkan GPS di setelan browser.';
                            break;
                        case 2:
                            this.pesan = 'Sinyal GPS lemah. Cari area luar ruangan.';
                            break;
                        case 3:
                            this.pesan = 'Batas waktu pemindaian habis. Coba lagi.';
                            break;
                        default:
                            this.pesan = 'Gagal memindai GPS. Coba lagi.';
                    }
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        },

        // Update state dari Livewire
        init() {
            this.$watch('$wire.state', val => { this.state = val; });
            this.$watch('$wire.pesan', val => { this.pesan = val; });
            this.$watch('$wire.waktuAbsen', val => { this.waktuAbsen = val; });
        }
    }
}
</script>

