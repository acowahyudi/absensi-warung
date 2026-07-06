@extends('layouts.admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold font-display text-ganjs-ink">Laporan Gaji</h1>
        <div class="flex items-center gap-3">
            {{-- Filter bulan/tahun --}}
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex gap-2 items-center">
                <select name="bulan" class="input py-2 w-36" onchange="this.form.submit()">
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $val => $nama)
                        <option value="{{ $val }}" {{ $bulan == $val ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="input py-2 w-28" onchange="this.form.submit()">
                    @foreach(range(now()->year, now()->year - 2) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>

            <form method="POST" action="{{ route('admin.laporan.generate') }}">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Generate
                </button>
            </form>

            @if($laporanList->isNotEmpty())
                <a href="{{ route('admin.laporan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                   class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Export Excel
                </a>
            @endif
        </div>
    </div>

    {{-- Summary totals --}}
    @if($laporanList->isNotEmpty())
        <div class="grid grid-cols-3 gap-4">
            <div class="card bg-ganjs-secondary-light border-ganjs-secondary/20">
                <p class="text-xs font-semibold text-ganjs-secondary uppercase tracking-wide">Total Gaji Bersih</p>
                <p class="text-2xl font-bold font-mono text-ganjs-secondary mt-1">
                    Rp {{ number_format($laporanList->sum('gaji_bersih'), 0, ',', '.') }}
                </p>
            </div>
            <div class="card bg-ganjs-warning-light border-ganjs-warning/20">
                <p class="text-xs font-semibold text-ganjs-warning uppercase tracking-wide">Total Uang Makan</p>
                <p class="text-2xl font-bold font-mono text-ganjs-warning mt-1">
                    Rp {{ number_format($laporanList->sum('total_uang_makan'), 0, ',', '.') }}
                </p>
            </div>
            <div class="card bg-ganjs-danger-light border-ganjs-danger/20">
                <p class="text-xs font-semibold text-ganjs-danger uppercase tracking-wide">Total Potongan</p>
                <p class="text-2xl font-bold font-mono text-ganjs-danger mt-1">
                    Rp {{ number_format($laporanList->sum('total_potongan'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="card p-0 overflow-hidden">
        @if($laporanList->isEmpty())
            <div class="text-center py-16 text-ganjs-ink-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 mx-auto mb-4 opacity-25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium">Belum ada laporan untuk periode ini</p>
                <p class="text-sm mt-1">Klik "Generate" untuk membuat laporan gaji bulan ini</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-ganjs">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Tidak Hadir</th>
                            <th class="text-right">Uang Makan</th>
                            <th class="text-right">Potongan</th>
                            <th class="text-right">Gaji Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporanList as $lap)
                            <tr>
                                <td class="font-semibold">{{ $lap->karyawan->pengguna->nama_lengkap }}</td>
                                <td class="text-center">
                                    <span class="badge-hadir">{{ $lap->total_hadir }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-terlambat">{{ $lap->total_terlambat }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-tidak_hadir">{{ $lap->total_tidak_hadir }}</span>
                                </td>
                                <td class="text-right font-mono text-sm text-ganjs-secondary">
                                    Rp {{ number_format($lap->total_uang_makan, 0, ',', '.') }}
                                </td>
                                <td class="text-right font-mono text-sm text-ganjs-danger">
                                    Rp {{ number_format($lap->total_potongan, 0, ',', '.') }}
                                </td>
                                <td class="text-right font-mono font-bold text-ganjs-ink">
                                    Rp {{ number_format($lap->gaji_bersih, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
