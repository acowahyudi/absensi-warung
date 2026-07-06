@extends('layouts.admin')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold font-display text-ganjs-ink">Lokasi Kantor</h1>
        <a href="{{ route('admin.lokasi.create') }}" class="btn-primary">+ Tambah Lokasi</a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($lokasiList as $lok)
            <div class="card-hover {{ $lok->is_aktif ? 'border-ganjs-secondary/40' : '' }}">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-ganjs-ink">{{ $lok->nama_lokasi }}</h3>
                            @if($lok->is_aktif)
                                <span class="badge-hadir text-xs">Aktif</span>
                            @endif
                        </div>
                        <p class="text-xs text-ganjs-ink-muted mt-1">
                            Radius: {{ $lok->radius_meter }} meter
                        </p>
                    </div>
                </div>
                <div class="bg-ganjs-bg rounded-xl p-3 mb-3">
                    <p class="text-xs font-mono text-ganjs-ink-muted">
                        {{ $lok->latitude }}, {{ $lok->longitude }}
                    </p>
                </div>
                <div class="flex gap-2">
                    @if(!$lok->is_aktif)
                        <form method="POST" action="{{ route('admin.lokasi.aktif', $lok) }}" class="flex-1">
                            @csrf
                            <button class="btn-secondary w-full justify-center text-xs py-2">Jadikan Aktif</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.lokasi.edit', $lok) }}" class="btn-ghost px-3 py-2 text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.lokasi.destroy', $lok) }}" onsubmit="return confirm('Hapus lokasi ini?')">
                        @csrf @method('DELETE')
                        <button class="btn-ghost px-3 py-2 text-xs text-ganjs-danger hover:bg-ganjs-danger-light">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="sm:col-span-3 card text-center py-12 text-ganjs-ink-muted">
                Belum ada lokasi kantor dikonfigurasi.
            </div>
        @endforelse
    </div>
</div>
@endsection
