<div class="space-y-4">
    {{-- Filter --}}
    <div class="flex gap-2">
        <select wire:model.live="filterBulan" class="input py-2 flex-1">
            @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $val => $nama)
                <option value="{{ $val }}">{{ $nama }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterTahun" class="input py-2 w-28">
            @foreach(range(now()->year, now()->year - 2) as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="card p-4">
            <p class="text-2xl font-bold text-ganjs-secondary">{{ $summary['hadir'] }}</p>
            <p class="text-xs text-ganjs-ink-muted font-medium">Hari Hadir</p>
        </div>
        <div class="card p-4">
            <p class="text-2xl font-bold text-ganjs-warning">{{ $summary['terlambat'] }}</p>
            <p class="text-xs text-ganjs-ink-muted font-medium">Hari Terlambat</p>
        </div>
        <div class="card p-4">
            <p class="text-2xl font-bold text-ganjs-danger">{{ $summary['tidak_hadir'] }}</p>
            <p class="text-xs text-ganjs-ink-muted font-medium">Tidak Hadir</p>
        </div>
        <div class="card p-4">
            <p class="text-xl font-bold font-mono text-ganjs-primary">
                Rp {{ number_format($summary['uang_makan'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-ganjs-ink-muted font-medium">Uang Makan</p>
        </div>
    </div>

    {{-- List --}}
    <div class="space-y-2">
        @forelse($riwayat as $item)
            <div class="card flex items-center gap-3 py-3">
                <x-status-icon :status="$item->status_kehadiran" />
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-ganjs-ink text-sm">
                        {{ $item->jadwal?->shift?->nama_shift ?? 'Shift' }}
                    </p>
                    <p class="text-xs text-ganjs-ink-muted">
                        Masuk <span class="font-mono">{{ $item->waktu_masuk->format('H:i') }}</span>
                        @if($item->waktu_keluar)
                            · Keluar <span class="font-mono">{{ $item->waktu_keluar->format('H:i') }}</span>
                        @endif
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <x-status-badge :status="$item->status_kehadiran" />
                    <p class="text-xs text-ganjs-ink-muted mt-1">
                        {{ $item->waktu_masuk->locale('id')->isoFormat('ddd, D MMM') }}
                    </p>
                    @if($item->uang_makan_diterima > 0)
                        <p class="text-xs font-mono text-ganjs-secondary font-semibold">
                            +Rp {{ number_format($item->uang_makan_diterima, 0, ',', '.') }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="card text-center py-12 text-ganjs-ink-muted">
                <p class="font-medium">Belum ada riwayat bulan ini</p>
            </div>
        @endforelse
    </div>

    {{ $riwayat->links() }}
</div>
