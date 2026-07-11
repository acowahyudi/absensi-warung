<div x-data="absenKeluar()" class="card border border-ganjs-secondary/15 bg-white/80 backdrop-blur-md relative overflow-hidden shadow-md animate-slide-up">
    <!-- Decorative background shape -->
    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-ganjs-secondary/5 rounded-full pointer-events-none"></div>

    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-ganjs-secondary/10 flex items-center justify-center flex-shrink-0 text-ganjs-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-2-2m0 0l2-2m-2 2h8m-9 4h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[10px] text-ganjs-ink-muted uppercase tracking-wider font-bold">Waktu Absen Masuk Anda</p>
            <p class="font-extrabold text-ganjs-secondary text-base">
                {{ $absensiHariIni ? $absensiHariIni->waktu_masuk->format('H:i') : '-' }}
            </p>
        </div>
        <div class="text-right">
            <span class="px-2.5 py-1 rounded-full text-[9px] font-bold bg-ganjs-secondary/10 text-ganjs-secondary border border-ganjs-secondary/20 uppercase tracking-wider">
                AKTIF
            </span>
        </div>
    </div>

    {{-- Panel Sudah Keluar / Success --}}
    <template x-if="state === 'sudah_keluar' || state === 'success'">
        <div class="p-3 bg-ganjs-secondary/10 border border-ganjs-secondary/20 rounded-xl text-center text-ganjs-secondary font-bold text-xs flex items-center justify-center gap-2 animate-[fade-in_0.3s_ease-out]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-text="pesan"></span>
        </div>
    </template>

    {{-- Form Absen Keluar --}}
    <template x-if="state !== 'sudah_keluar' && state !== 'success'">
        <div class="space-y-3">
            <template x-if="pesan && state === 'error'">
                <div class="p-3 bg-ganjs-danger/10 border border-ganjs-danger/20 rounded-xl text-xs text-ganjs-danger font-bold flex items-center gap-1.5 animate-slide-up">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span x-text="pesan"></span>
                </div>
            </template>

            <button @click="handleKeluar"
                    :disabled="state === 'loading'"
                    class="w-full btn-primary bg-gradient-to-r from-ganjs-secondary to-[#224426] hover:from-[#224426] hover:to-[#17301B] shadow-[0_4px_16px_rgba(47,82,51,0.2)] py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all duration-300 active:scale-95 disabled:opacity-60">
                <template x-if="state === 'loading'">
                    <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </template>
                <span class="text-xs tracking-wider uppercase" x-text="state === 'loading' ? 'Memindai Lokasi...' : 'ABSEN KELUAR SEKARANG'"></span>
            </button>
        </div>
    </template>
</div>

<script>
function absenKeluar() {
    return {
        state: @js($state),
        pesan: @js($pesan),
        waktuKeluar: @js($waktuKeluar),

        handleKeluar() {
            this.state = 'loading';
            navigator.geolocation.getCurrentPosition(
                (pos) => @this.prosesKeluar(pos.coords.latitude, pos.coords.longitude),
                (err) => {
                    this.state = 'error';
                    this.pesan = 'Tidak bisa membaca lokasi. Izinkan akses GPS.';
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }
    }
}
</script>

