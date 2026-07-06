<x-karyawan-layout>
    <x-slot:title>Absensi</x-slot:title>

    <div class="px-4 pt-6 pb-2">
        {{-- Header sapaan --}}
        <div class="mb-6 animate-fade-in">
            <p class="text-ganjs-ink-muted text-sm">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
            <h1 class="text-2xl font-bold font-display text-ganjs-ink mt-1">
                Selamat {{ now()->hour < 12 ? 'pagi' : (now()->hour < 17 ? 'siang' : 'malam') }},
                {{ explode(' ', auth()->user()->nama_lengkap)[0] }} 👋
            </h1>

            {{-- Jam real-time --}}
            <div x-data="clock()" class="mt-2">
                <p class="text-4xl font-mono font-bold text-ganjs-primary" x-text="time"></p>
            </div>
        </div>

        {{-- Info shift hari ini --}}
        @if($jadwalHariIni)
            <div class="card mb-6 flex items-center gap-3 animate-slide-up">
                <div class="w-10 h-10 rounded-xl bg-ganjs-primary-light flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-ganjs-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-ganjs-ink-muted font-medium">Shift hari ini</p>
                    <p class="font-bold text-ganjs-ink">
                        {{ $jadwalHariIni->shift->nama_shift }}
                        <span class="font-normal text-ganjs-ink-muted text-sm">
                            · {{ substr($jadwalHariIni->shift->jam_mulai, 0, 5) }}–{{ substr($jadwalHariIni->shift->jam_selesai, 0, 5) }}
                        </span>
                    </p>
                </div>
            </div>
        @elseif(!$absensiHariIni)
            <div class="card mb-6 border-ganjs-warning/30 bg-ganjs-warning-light">
                <p class="text-sm font-medium text-ganjs-warning">⚠️ Kamu tidak memiliki jadwal kerja hari ini.</p>
            </div>
        @endif

        {{-- Komponen Absen Masuk --}}
        <livewire:absensi.absen-masuk />

        {{-- Komponen Absen Keluar (tampil setelah absen masuk) --}}
        @if($absensiHariIni && !$absensiHariIni->waktu_keluar)
            <div class="mt-4">
                <livewire:absensi.absen-keluar />
            </div>
        @endif

        {{-- Riwayat 3 hari terakhir --}}
        @if($riwayat3Hari->isNotEmpty())
            <div class="mt-8">
                <h2 class="text-sm font-semibold text-ganjs-ink-muted uppercase tracking-wide mb-3">Riwayat Terakhir</h2>
                <div class="space-y-2">
                    @foreach($riwayat3Hari as $item)
                        <div class="card-hover flex items-center gap-3">
                            <x-status-icon :status="$item->status_kehadiran" />
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-ganjs-ink text-sm">
                                    {{ $item->jadwal?->shift?->nama_shift ?? '-' }}
                                </p>
                                <p class="text-xs text-ganjs-ink-muted">
                                    Masuk {{ $item->waktu_masuk->format('H:i') }}
                                    @if($item->waktu_keluar)
                                        · Keluar {{ $item->waktu_keluar->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <x-status-badge :status="$item->status_kehadiran" />
                                <p class="text-xs text-ganjs-ink-muted mt-1">
                                    {{ $item->waktu_masuk->locale('id')->isoFormat('ddd, D MMM') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function clock() {
            return {
                time: '',
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const now = new Date();
                    this.time = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit', minute: '2-digit', second: '2-digit',
                        hour12: false
                    });
                }
            }
        }
    </script>
    @endpush
</x-karyawan-layout>
