@extends('layouts.admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold font-display text-ganjs-ink">Jadwal Karyawan</h1>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex gap-2">
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="input py-2" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-5">
        {{-- Form Tambah Jadwal --}}
        <div class="card">
            <h2 class="font-bold text-ganjs-ink mb-4">Tambah Jadwal</h2>
            <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="tanggal_kerja" value="{{ $tanggal }}">

                <div>
                    <label class="label">Karyawan</label>
                    <select name="id_karyawan" class="input" required>
                        <option value="">Pilih karyawan...</option>
                        @foreach($karyawanList as $k)
                            <option value="{{ $k->id_karyawan }}">{{ $k->pengguna->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Shift</label>
                    <select name="id_shift" class="input" required>
                        <option value="">Pilih shift...</option>
                        @foreach($shiftList as $s)
                            <option value="{{ $s->id_shift }}">
                                {{ $s->nama_shift }} ({{ substr($s->jam_mulai, 0, 5) }}–{{ substr($s->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Simpan Jadwal</button>
            </form>
        </div>

        {{-- Daftar Jadwal Hari Ini --}}
        <div class="lg:col-span-2 card p-0">
            <div class="p-5 border-b border-ganjs-border">
                <p class="font-bold text-ganjs-ink">
                    Jadwal {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
                <p class="text-sm text-ganjs-ink-muted">{{ $jadwalList->count() }} karyawan dijadwalkan</p>
            </div>

            @if($jadwalList->isEmpty())
                <div class="text-center py-12 text-ganjs-ink-muted">
                    <p class="font-medium">Belum ada jadwal untuk tanggal ini</p>
                </div>
            @else
                <table class="table-ganjs">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Shift</th>
                            <th>Status Absensi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalList as $j)
                            <tr class="{{ $j->status_jadwal === 'dibatalkan' ? 'opacity-50' : '' }}">
                                <td class="font-semibold">{{ $j->karyawan->pengguna->nama_lengkap }}</td>
                                <td>
                                    <span class="text-sm">{{ $j->shift->nama_shift }}</span>
                                    <span class="text-xs text-ganjs-ink-muted">· {{ substr($j->shift->jam_mulai, 0, 5) }}–{{ substr($j->shift->jam_selesai, 0, 5) }}</span>
                                </td>
                                <td>
                                    @if($j->absensi)
                                        <x-status-badge :status="$j->absensi->status_kehadiran" />
                                    @else
                                        <span class="text-xs text-ganjs-ink-muted">Belum absen</span>
                                    @endif
                                </td>
                                <td>
                                    @if($j->status_jadwal === 'aktif')
                                        <form method="POST" action="{{ route('admin.jadwal.destroy', $j) }}" onsubmit="return confirm('Batalkan jadwal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-ghost px-2 py-1 text-xs text-ganjs-danger hover:bg-ganjs-danger-light">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-ganjs-ink-muted italic">Dibatalkan</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
