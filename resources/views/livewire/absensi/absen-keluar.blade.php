<div x-data="absenKeluar()" class="card border-ganjs-secondary/30 bg-ganjs-secondary-light/30">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-8 h-8 rounded-lg bg-ganjs-secondary flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>
        <p class="font-semibold text-ganjs-secondary text-sm">Absen Keluar</p>
    </div>

    <template x-if="state === 'sudah_keluar'">
        <p class="text-sm text-ganjs-secondary font-medium" x-text="pesan"></p>
    </template>

    <template x-if="state !== 'sudah_keluar'">
        <div>
            <template x-if="pesan && state === 'error'">
                <p class="text-sm text-ganjs-danger font-medium mb-3" x-text="pesan"></p>
            </template>

            <button @click="handleKeluar"
                    :disabled="state === 'loading'"
                    class="btn-secondary w-full justify-center disabled:opacity-60">
                <template x-if="state === 'loading'">
                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </template>
                <span x-text="state === 'loading' ? 'Mencari lokasi...' : 'Absen Keluar Sekarang'"></span>
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
