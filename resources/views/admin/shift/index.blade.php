@extends('layouts.admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold font-display text-ganjs-ink">Daftar Shift</h1>
        <a href="{{ route('admin.shift.create') }}" class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Tambah Shift
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($shifts as $shift)
            <div class="card-hover">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-ganjs-ink">{{ $shift->nama_shift }}</h3>
                        <p class="text-2xl font-mono font-bold text-ganjs-primary mt-1">
                            {{ substr($shift->jam_mulai, 0, 5) }}
                            <span class="text-ganjs-ink-muted text-lg font-normal">–</span>
                            {{ substr($shift->jam_selesai, 0, 5) }}
                        </p>
                        <p class="text-xs text-ganjs-ink-muted mt-1">
                            Toleransi: {{ $shift->toleransi_menit }} menit ·
                            {{ $shift->jadwal_karyawan_count }} jadwal
                        </p>
                    </div>
                    <div class="flex gap-1 flex-shrink-0">
                        <a href="{{ route('admin.shift.edit', $shift) }}" class="btn-ghost px-2 py-1 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.shift.destroy', $shift) }}" onsubmit="return confirm('Hapus shift ini?')">
                            @csrf @method('DELETE')
                            <button class="btn-ghost px-2 py-1 text-xs text-ganjs-danger hover:bg-ganjs-danger-light">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 card text-center py-12 text-ganjs-ink-muted">
                Belum ada shift. <a href="{{ route('admin.shift.create') }}" class="text-ganjs-primary font-semibold">Tambahkan sekarang →</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
