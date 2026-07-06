<x-karyawan-layout>
    <x-slot:title>Uang Makan</x-slot:title>

    <div class="px-4 pt-6">
        <h1 class="text-xl font-bold font-display text-ganjs-ink mb-6">Uang Makan</h1>

        @php
            $karyawan = auth()->user()->karyawan;
            $bulan = request('bulan', now()->month);
            $tahun = request('tahun', now()->year);
            $absensiList = $karyawan ? \App\Models\Absensi::where('id_karyawan', $karyawan->id_karyawan)
                ->whereMonth('waktu_masuk', $bulan)->whereYear('waktu_masuk', $tahun)
                ->where('uang_makan_diterima', '>', 0)->orderByDesc('waktu_masuk')->get() : collect();
            $total = $absensiList->sum('uang_makan_diterima');
        @endphp

        {{-- Total kartu --}}
        <div class="card mb-6 bg-gradient-to-br from-ganjs-primary to-ganjs-primary-dark text-white border-0 shadow-btn">
            <p class="text-sm font-medium opacity-80">Total Uang Makan Bulan Ini</p>
            <p class="text-4xl font-bold font-mono mt-2">Rp {{ number_format($total, 0, ',', '.') }}</p>
            <p class="text-xs opacity-70 mt-1">{{ $absensiList->count() }} hari hadir tepat waktu</p>
        </div>

        {{-- Daftar per hari --}}
        <div class="space-y-2">
            @forelse($absensiList as $item)
                <div class="card-hover flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-sm">
                            {{ $item->waktu_masuk->locale('id')->isoFormat('dddd, D MMMM') }}
                        </p>
                        <p class="text-xs text-ganjs-ink-muted">
                            Masuk <span class="font-mono">{{ $item->waktu_masuk->format('H:i') }}</span>
                        </p>
                    </div>
                    <p class="font-mono font-bold text-ganjs-secondary">
                        +Rp {{ number_format($item->uang_makan_diterima, 0, ',', '.') }}
                    </p>
                </div>
            @empty
                <div class="card text-center py-12 text-ganjs-ink-muted">
                    <p class="font-medium">Belum ada uang makan bulan ini</p>
                    <p class="text-sm mt-1">Hadir tepat waktu untuk mendapat uang makan harian</p>
                </div>
            @endforelse
        </div>
    </div>
</x-karyawan-layout>
