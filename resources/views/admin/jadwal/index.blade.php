@extends('layouts.admin')
@section('title', 'Atur Jadwal Mingguan')

@section('content')
<div class="space-y-6">
    {{-- Header & Navigasi Minggu --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-card shadow-card border border-ganjs-border/50">
        <div>
            <h1 class="text-xl font-bold font-display text-ganjs-ink">Jadwal Kerja Karyawan</h1>
            <p class="text-sm text-ganjs-ink-muted mt-1">
                Periode: <span class="font-semibold text-ganjs-ink">{{ $startOfWeek->locale('id')->isoFormat('D MMMM Y') }}</span> s.d. <span class="font-semibold text-ganjs-ink">{{ $startOfWeek->copy()->addDays(6)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        
        {{-- Navigation Controls --}}
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.jadwal.index', ['tanggal' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" 
               class="btn-secondary py-2 px-3 text-xs flex items-center gap-1 shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Minggu Lalu
            </a>

            <a href="{{ route('admin.jadwal.index', ['tanggal' => today()->format('Y-m-d')]) }}" 
               class="btn-secondary py-2 px-3 text-xs shadow-none {{ $selectedDate->isToday() ? 'bg-ganjs-bg border-ganjs-primary text-ganjs-primary' : '' }}">
                Hari Ini
            </a>

            <a href="{{ route('admin.jadwal.index', ['tanggal' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" 
               class="btn-secondary py-2 px-3 text-xs flex items-center gap-1 shadow-none">
                Minggu Depan
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex items-center gap-2 ml-2">
                <input type="date" name="tanggal" value="{{ $selectedDate->format('Y-m-d') }}" 
                       class="input py-1.5 px-3 text-xs w-36 shadow-none" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    {{-- Grid Jadwal Mingguan --}}
    <div class="card p-0 overflow-hidden">
        <form method="POST" action="{{ route('admin.jadwal.weekly') }}">
            @csrf
            <input type="hidden" name="start_date" value="{{ $startOfWeek->format('Y-m-d') }}">

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-ganjs">
                    <thead>
                        <tr class="bg-ganjs-bg border-b border-ganjs-border">
                            <th class="p-4 font-semibold text-sm text-ganjs-ink-muted w-52 sticky left-0 bg-ganjs-bg z-10 border-r border-ganjs-border">
                                Nama Karyawan
                            </th>
                            @foreach($days as $day)
                                <th class="p-4 font-semibold text-sm text-center border-r border-ganjs-border/60 {{ $day->isToday() ? 'bg-ganjs-primary-light/30' : '' }}">
                                    <div class="text-xs uppercase tracking-wider text-ganjs-ink-muted">
                                        {{ $day->locale('id')->isoFormat('dddd') }}
                                    </div>
                                    <div class="text-sm font-bold text-ganjs-ink mt-0.5">
                                        {{ $day->format('d M') }}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawanList as $karyawan)
                            <tr class="border-b border-ganjs-border/50 hover:bg-ganjs-bg/30 transition-colors">
                                {{-- Nama Karyawan (Sticky Column) --}}
                                <td class="p-4 font-semibold text-ganjs-ink text-sm bg-white sticky left-0 z-10 border-r border-ganjs-border shadow-[4px_0_8px_-4px_rgba(0,0,0,0.05)]">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center text-sm font-bold flex-shrink-0">
                                            {{ substr($karyawan->pengguna->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-ganjs-ink leading-tight">{{ $karyawan->pengguna->nama_lengkap }}</p>
                                            <p class="text-xs text-ganjs-ink-muted font-mono mt-0.5">{{ $karyawan->nik_karyawan }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Sel Hari (Senin - Minggu) --}}
                                @foreach($days as $day)
                                    @php
                                        $dateStr = $day->format('Y-m-d');
                                        $mapped = $jadwalMap[$karyawan->id_karyawan][$dateStr] ?? null;
                                        $selectedShiftId = $mapped['id_shift'] ?? null;
                                        $hasAbsensi = $mapped['has_absensi'] ?? false;
                                        $isToday = $day->isToday();
                                    @endphp
                                    <td class="p-3 border-r border-ganjs-border/40 align-middle text-center {{ $isToday ? 'bg-ganjs-primary-light/10' : '' }}">
                                        @if($hasAbsensi)
                                            {{-- Lock dropdown if attendance already exists --}}
                                            <div class="flex flex-col items-center gap-1">
                                                <input type="hidden" name="jadwal[{{ $karyawan->id_karyawan }}][{{ $dateStr }}]" value="{{ $selectedShiftId }}">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-ganjs-secondary-light text-ganjs-secondary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    {{ $shiftList->firstWhere('id_shift', $selectedShiftId)?->nama_shift ?? 'Shift' }}
                                                </span>
                                                <span class="text-[9px] text-ganjs-ink-muted">Sudah Absen</span>
                                            </div>
                                        @else
                                            {{-- Dropdown shift normal --}}
                                            <select name="jadwal[{{ $karyawan->id_karyawan }}][{{ $dateStr }}]" 
                                                    class="w-full min-w-[110px] text-xs py-1.5 px-2 bg-ganjs-bg/50 border border-ganjs-border rounded-lg focus:border-ganjs-primary focus:ring-1 focus:ring-ganjs-primary/20 transition-all font-medium">
                                                <option value="" class="text-ganjs-ink-muted font-normal">🏖️ Libur (Off)</option>
                                                @foreach($shiftList as $shift)
                                                    <option value="{{ $shift->id_shift }}" {{ $selectedShiftId == $shift->id_shift ? 'selected' : '' }}>
                                                        {{ $shift->nama_shift }} ({{ substr($shift->jam_mulai, 0, 5) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-ganjs-ink-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="font-medium text-sm">Tidak ada karyawan aktif yang ditemukan.</p>
                                    <p class="text-xs mt-1">Tambahkan karyawan terlebih dahulu di modul karyawan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Aksi --}}
            @if($karyawanList->isNotEmpty())
                <div class="p-5 bg-ganjs-bg border-t border-ganjs-border flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-ganjs-secondary"></span>
                        <span class="text-xs text-ganjs-ink-muted">Sel hijau terkunci otomatis apabila karyawan sudah memiliki record absen masuk pada hari itu.</span>
                    </div>
                    <button type="submit" class="btn-primary py-2.5 px-6 font-bold shadow-btn">
                        Simpan Jadwal Mingguan
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
