@extends('layouts.admin')

@section('content')
<div class="space-y-5">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold font-display text-ganjs-ink">Daftar Karyawan</h1>
            <p class="text-sm text-ganjs-ink-muted mt-0.5">{{ $karyawanList->total() }} karyawan terdaftar</p>
        </div>
        <a href="{{ route('admin.karyawan.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Tambah Karyawan
        </a>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="table-ganjs">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jenis Kelamin</th>
                        <th class="text-right">Gaji Pokok</th>
                        <th class="text-right">Uang Makan/hari</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanList as $k)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-ganjs-primary-light text-ganjs-primary flex items-center justify-center text-sm font-bold flex-shrink-0">
                                        {{ substr($k->pengguna->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $k->pengguna->nama_lengkap }}</p>
                                        <p class="text-xs text-ganjs-ink-muted">{{ $k->pengguna->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="font-mono text-sm">{{ $k->nik_karyawan }}</td>
                            <td>{{ $k->jenis_kelamin }}</td>
                            <td class="text-right font-mono text-sm">Rp {{ number_format($k->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="text-right font-mono text-sm">Rp {{ number_format($k->uang_makan_per_hari, 0, ',', '.') }}</td>
                            <td>
                                @if($k->is_aktif)
                                    <span class="badge-hadir">Aktif</span>
                                @else
                                    <span class="badge-tidak_hadir">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-sm text-ganjs-ink-muted">{{ $k->tanggal_bergabung->format('d M Y') }}</td>
                            <td>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.karyawan.edit', $k) }}" class="btn-ghost px-2 py-1 text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.karyawan.destroy', $k) }}" onsubmit="return confirm('Nonaktifkan karyawan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ghost px-2 py-1 text-xs text-ganjs-danger hover:bg-ganjs-danger-light">
                                            Nonaktifkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-ganjs-ink-muted">
                                Belum ada karyawan. <a href="{{ route('admin.karyawan.create') }}" class="text-ganjs-primary font-semibold">Tambahkan sekarang →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($karyawanList->hasPages())
            <div class="px-5 py-4 border-t border-ganjs-border">
                {{ $karyawanList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
