<div x-data="absenMasuk()" class="flex flex-col items-center py-4">

    {{-- Tombol Absen Lingkaran Besar --}}
    <div class="relative mb-6" @click="handleClick">
        {{-- Pulse ring background (hanya saat idle) --}}
        <div x-show="state === 'idle'"
             class="absolute inset-0 rounded-full bg-ganjs-primary/20 animate-ping scale-110">
        </div>

        {{-- Tombol utama --}}
        <button
            :class="{
                'absen-btn-idle': state === 'idle',
                'absen-btn-loading': state === 'loading',
                'absen-btn-success': state === 'success' || state === 'sudah_absen',
                'absen-btn-error': state === 'error' || state === 'no_jadwal',
            }"
            :disabled="state === 'loading' || state === 'success' || state === 'sudah_absen'"
            aria-label="Tombol Absen Masuk"
            class="w-48 h-48 rounded-full text-white font-bold text-lg relative z-10 transition-all duration-300 active:scale-95 disabled:cursor-default shadow-btn flex flex-col items-center justify-center gap-2">

            {{-- Icon berdasarkan state --}}
            {{-- Idle --}}
            <template x-if="state === 'idle'">
                <div class="flex flex-col items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-bold">ABSEN MASUK</span>
                </div>
            </template>

            {{-- Loading --}}
            <template x-if="state === 'loading'">
                <div class="flex flex-col items-center gap-2">
                    <svg class="animate-spin w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Mencari lokasi...</span>
                </div>
            </template>

            {{-- Success / Sudah Absen --}}
            <template x-if="state === 'success' || state === 'sudah_absen'">
                <div class="flex flex-col items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-bold" x-text="waktuAbsen ? `Pukul ${waktuAbsen}` : 'Tercatat!'"></span>
                </div>
            </template>

            {{-- Error --}}
            <template x-if="state === 'error'">
                <div class="flex flex-col items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs font-semibold text-center px-2">Coba Lagi</span>
                </div>
            </template>

            {{-- No jadwal --}}
            <template x-if="state === 'no_jadwal'">
                <div class="flex flex-col items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs font-semibold">Tidak ada jadwal</span>
                </div>
            </template>
        </button>
    </div>

    {{-- Status chip --}}
    <div class="min-h-[2.5rem] flex items-center">
        <template x-if="pesan">
            <div x-transition
                 :class="{
                    'bg-ganjs-secondary-light text-ganjs-secondary': state === 'success' || state === 'sudah_absen',
                    'bg-ganjs-danger-light text-ganjs-danger': state === 'error',
                    'bg-ganjs-warning-light text-ganjs-warning': state === 'loading',
                    'bg-ganjs-bg text-ganjs-ink-muted': state === 'no_jadwal',
                 }"
                 class="px-4 py-2 rounded-full text-sm font-semibold text-center max-w-xs animate-slide-up">
                <span x-text="pesan"></span>
            </div>
        </template>

        <template x-if="!pesan && state === 'idle'">
            <p class="text-sm text-ganjs-ink-muted text-center">Tekan untuk absen masuk</p>
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
            this.pesan = 'Mencari lokasi GPS...';

            if (!navigator.geolocation) {
                this.state = 'error';
                this.pesan = 'Browser kamu tidak mendukung GPS. Gunakan Chrome di Android.';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Kirim ke Livewire untuk validasi server-side
                    @this.prosesAbsen(lat, lon);
                },
                (error) => {
                    this.state = 'error';
                    switch (error.code) {
                        case 1:
                            this.pesan = 'Akses lokasi ditolak. Izinkan GPS di pengaturan browser.';
                            break;
                        case 2:
                            this.pesan = 'Lokasi tidak dapat ditentukan. Coba di luar ruangan.';
                            break;
                        case 3:
                            this.pesan = 'Waktu habis. Coba lagi.';
                            break;
                        default:
                            this.pesan = 'Gagal membaca lokasi. Coba lagi.';
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
