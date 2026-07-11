<x-karyawan-layout>
    <x-slot:title>Absensi Karyawan</x-slot:title>

    <div class="px-4 pt-6 pb-4 space-y-6">
        
        {{-- Header Card dengan Greeting Dinamis & Jam Digital --}}
        @php
            $hour = now()->hour;
            if ($hour >= 4 && $hour < 11) {
                $greetingBg = 'from-[#F2994A] via-[#E88147] to-ganjs-primary text-white';
                $greetingText = 'Selamat Pagi';
                $greetingIcon = '🌅';
            } elseif ($hour >= 11 && $hour < 16) {
                $greetingBg = 'from-ganjs-primary via-[#D66D46] to-[#A33405] text-white';
                $greetingText = 'Selamat Siang';
                $greetingIcon = '☀️';
            } elseif ($hour >= 16 && $hour < 19) {
                $greetingBg = 'from-[#D66D46] via-[#9E3109] to-ganjs-ink text-white';
                $greetingText = 'Selamat Sore';
                $greetingIcon = '🌇';
            } else {
                $greetingBg = 'from-[#2F5233] via-[#1C3620] to-ganjs-ink text-white';
                $greetingText = 'Selamat Malam';
                $greetingIcon = '🌙';
            }
        @endphp

        <div class="relative rounded-3xl bg-gradient-to-br {{ $greetingBg }} p-6 shadow-lg overflow-hidden animate-fade-in">
            <!-- Glassmorphic Decorative Circles -->
            <div class="absolute -right-10 -bottom-10 w-36 h-36 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
            <div class="absolute -left-12 -top-12 w-28 h-28 rounded-full bg-white/5 blur-lg pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- User Info -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white text-xl font-extrabold shadow-sm flex-shrink-0">
                        {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs text-white/80 font-semibold tracking-wide">
                            {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </p>
                        <h1 class="text-xl font-extrabold font-display mt-0.5">
                            {{ $greetingText }}, {{ explode(' ', auth()->user()->nama_lengkap)[0] }} {{ $greetingIcon }}
                        </h1>
                    </div>
                </div>

                <!-- Jam Real-Time Glowing -->
                <div x-data="clock()" class="flex justify-start md:justify-end">
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl flex items-baseline justify-center gap-1.5 shadow-[inset_0_2px_4px_rgba(255,255,255,0.15)] border border-white/10">
                        <span class="text-2xl font-mono font-bold tracking-wider" x-text="time"></span>
                        <span class="text-[10px] font-bold text-white/70 uppercase tracking-widest" x-text="seconds"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Shift Hari Ini / No Jadwal --}}
        @if($jadwalHariIni)
            <div class="card p-5 animate-slide-up relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-ganjs-primary/5 rounded-full -mr-8 -mt-8 pointer-events-none"></div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-ganjs-primary-light flex items-center justify-center flex-shrink-0 text-ganjs-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] text-ganjs-ink-muted uppercase tracking-wider font-bold">Shift Kerja Hari Ini</p>
                        <p class="font-extrabold text-ganjs-ink text-base">
                            {{ $jadwalHariIni->shift->nama_shift }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-ganjs-primary/10 text-ganjs-primary border border-ganjs-primary/20">
                            {{ substr($jadwalHariIni->shift->jam_mulai, 0, 5) }} - {{ substr($jadwalHariIni->shift->jam_selesai, 0, 5) }}
                        </span>
                    </div>
                </div>

                {{-- Timeline progress bar --}}
                @php
                    $mulai = \Carbon\Carbon::parse($jadwalHariIni->shift->jam_mulai);
                    $selesai = \Carbon\Carbon::parse($jadwalHariIni->shift->jam_selesai);
                    $now = now();
                    
                    if ($selesai->lt($mulai)) {
                        $selesai->addDay();
                    }
                    
                    $totalDurasi = $mulai->diffInSeconds($selesai);
                    $elapsed = $mulai->diffInSeconds($now, false);
                    
                    $percent = 0;
                    $isShiftActive = false;
                    
                    if ($now->between($mulai, $selesai)) {
                        $percent = min(100, max(0, ($elapsed / $totalDurasi) * 100));
                        $isShiftActive = true;
                    } elseif ($now->gt($selesai)) {
                        $percent = 100;
                    }
                @endphp

                <div class="mt-4 pt-3 border-t border-ganjs-border/40">
                    <div class="flex justify-between text-xs text-ganjs-ink-muted mb-1.5 font-medium">
                        <span>Jam Masuk</span>
                        @if($isShiftActive)
                            <span class="text-ganjs-primary font-bold animate-pulse">Shift Aktif ({{ round($percent) }}%)</span>
                        @elseif($now->gt($selesai))
                            <span class="text-ganjs-secondary font-bold">Shift Selesai</span>
                        @else
                            <span>Belum mulai</span>
                        @endif
                        <span>Jam Pulang</span>
                    </div>
                    <div class="w-full h-2 bg-ganjs-bg rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-ganjs-primary to-ganjs-primary-dark rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-ganjs-ink mt-1.5">
                        <span>{{ substr($jadwalHariIni->shift->jam_mulai, 0, 5) }}</span>
                        <span>{{ substr($jadwalHariIni->shift->jam_selesai, 0, 5) }}</span>
                    </div>
                </div>
            </div>
        @elseif(!$absensiHariIni)
            <div class="card p-4 border border-ganjs-warning/20 bg-ganjs-warning-light/35 flex items-start gap-2.5 rounded-2xl animate-slide-up">
                <span class="text-lg">⚠️</span>
                <div>
                    <p class="text-sm font-bold text-ganjs-warning">Jadwal Kosong</p>
                    <p class="text-xs text-ganjs-ink-muted mt-0.5">Kamu tidak memiliki jadwal kerja yang terdaftar hari ini.</p>
                </div>
            </div>
        @endif

        {{-- Komponen Absen Masuk --}}
        <livewire:absensi.absen-masuk />

        {{-- Komponen Absen Keluar (tampil setelah absen masuk) --}}
        @if($absensiHariIni && !$absensiHariIni->waktu_keluar)
            <div class="animate-slide-up">
                <livewire:absensi.absen-keluar />
            </div>
        @endif

        {{-- Riwayat 3 hari terakhir --}}
        @if($riwayat3Hari->isNotEmpty())
            <div class="pt-2">
                <div class="flex items-center justify-between mb-3.5">
                    <h2 class="text-xs font-extrabold text-ganjs-ink-muted uppercase tracking-wider">Riwayat 3 Hari Terakhir</h2>
                    <a href="{{ route('karyawan.riwayat') }}" class="text-xs font-bold text-ganjs-primary hover:text-ganjs-primary-dark transition-colors">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @foreach($riwayat3Hari as $item)
                        @php
                            $statusClass = '';
                            if ($item->status_kehadiran === 'hadir') {
                                $statusClass = 'border-l-4 border-ganjs-secondary';
                            } elseif ($item->status_kehadiran === 'terlambat') {
                                $statusClass = 'border-l-4 border-ganjs-warning';
                            } else {
                                $statusClass = 'border-l-4 border-ganjs-danger';
                            }
                        @endphp
                        <div class="card-hover flex items-center gap-3.5 p-4 rounded-2xl {{ $statusClass }}">
                            <x-status-icon :status="$item->status_kehadiran" />
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-ganjs-ink text-sm">
                                    {{ $item->jadwal?->shift?->nama_shift ?? 'Shift' }}
                                </p>
                                <p class="text-xs text-ganjs-ink-muted mt-0.5">
                                    Masuk: <span class="font-mono font-medium">{{ $item->waktu_masuk->format('H:i') }}</span>
                                    @if($item->waktu_keluar)
                                        · Keluar: <span class="font-mono font-medium">{{ $item->waktu_keluar->format('H:i') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <x-status-badge :status="$item->status_kehadiran" />
                                <p class="text-[10px] text-ganjs-ink-muted font-bold mt-1 uppercase tracking-wide">
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
                seconds: '',
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const now = new Date();
                    this.time = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit', minute: '2-digit',
                        hour12: false
                    });
                    this.seconds = now.toLocaleTimeString('id-ID', {
                        second: '2-digit'
                    });
                }
            }
        }
    </script>
    @endpush
</x-karyawan-layout>
