@extends('layouts.admin')

@section('content')
<div class="max-w-md">
    <div class="mb-5">
        <a href="{{ route('admin.shift.index') }}" class="btn-ghost px-0 text-ganjs-ink-muted">← Kembali</a>
        <h1 class="text-xl font-bold font-display text-ganjs-ink mt-2">Edit Shift: {{ $shift->nama_shift }}</h1>
    </div>
    <div class="card">
        <form method="POST" action="{{ route('admin.shift.update', $shift) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="label">Nama Shift</label>
                <input type="text" name="nama_shift" value="{{ old('nama_shift', $shift->nama_shift) }}" class="input" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', substr($shift->jam_mulai, 0, 5)) }}" class="input" required>
                </div>
                <div>
                    <label class="label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', substr($shift->jam_selesai, 0, 5)) }}" class="input" required>
                </div>
            </div>
            <div>
                <label class="label">Toleransi Terlambat (menit)</label>
                <input type="number" name="toleransi_menit" value="{{ old('toleransi_menit', $shift->toleransi_menit) }}" class="input" min="0" max="120" required>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan Perubahan</button>
                <a href="{{ route('admin.shift.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
