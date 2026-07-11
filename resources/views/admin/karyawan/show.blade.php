@extends('layouts.admin')
@section('title', 'Detail Karyawan')

@section('content')
<div class="space-y-5 max-w-2xl">
    <div class="mb-2">
        <a href="{{ route('admin.karyawan.index') }}" class="btn-ghost px-0 text-ganjs-ink-muted">← Kembali</a>
    </div>

    {{-- Header karyawan --}}
    <div class="card flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-ganjs-primary flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
            {{ substr($karyawan->pengguna->nama_lengkap, 0, 1) }}
        </div>
        <div>
            <h1 class="text-xl font-bold font-display text-ganjs-ink">{{ $karyawan->pengguna->nama_lengkap }}</h1>
            <p class="text-ganjs-ink-muted text-sm">{{ $karyawan->pengguna->email }}</p>
            <p class="font-mono text-xs text-ganjs-ink-muted mt-1">{{ $karyawan->nik_karyawan }}</p>
        </div>
        <div class="ml-auto flex gap-2">
            @if($karyawan->is_aktif)
                <span class="badge-hadir">Aktif</span>
            @else
                <span class="badge-tidak_hadir">Nonaktif</span>
            @endif
            <a href="{{ route('admin.karyawan.edit', $karyawan) }}" class="btn-secondary text-sm">Edit</a>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="card">
            <h2 class="font-bold text-ganjs-ink mb-3">Informasi Kepegawaian</h2>
            <div class="space-y-2.5">
                <div class="flex justify-between text-sm">
                    <span class="text-ganjs-ink-muted">Jenis Kelamin</span>
                    <span class="font-semibold">{{ $karyawan->jenis_kelamin }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-ganjs-ink-muted">Bergabung</span>
                    <span class="font-semibold">{{ $karyawan->tanggal_bergabung->locale('id')->isoFormat('D MMMM Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-ganjs-ink-muted">Gaji Pokok</span>
                    <span class="font-mono font-bold text-ganjs-primary">Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-ganjs-ink-muted">Uang Makan/hari</span>
                    <span class="font-mono font-bold text-ganjs-secondary">Rp {{ number_format($karyawan->uang_makan_per_hari, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm border-t border-ganjs-border/40 pt-2 mt-2">
                    <span class="text-ganjs-ink-muted">Lokasi Absen</span>
                    <span class="font-semibold text-ganjs-ink">{{ $karyawan->lokasi->nama_lokasi ?? 'Default (Aktif)' }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="font-bold text-ganjs-ink mb-3">Riwayat Absensi Terakhir</h2>
            @if($karyawan->absensi->isEmpty())
                <p class="text-sm text-ganjs-ink-muted">Belum ada riwayat absensi</p>
            @else
                <div class="space-y-2">
                    @foreach($karyawan->absensi as $ab)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-ganjs-ink-muted">{{ $ab->waktu_masuk->locale('id')->isoFormat('ddd, D MMM') }}</span>
                            <x-status-badge :status="$ab->status_kehadiran" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
