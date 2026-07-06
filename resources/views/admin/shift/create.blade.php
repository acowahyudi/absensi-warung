@extends('layouts.admin')

@section('content')
<div class="max-w-md">
    <div class="mb-5">
        <a href="{{ route('admin.shift.index') }}" class="btn-ghost px-0 text-ganjs-ink-muted">← Kembali</a>
        <h1 class="text-xl font-bold font-display text-ganjs-ink mt-2">Tambah Shift Baru</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.shift.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="label">Nama Shift</label>
                <input type="text" name="nama_shift" value="{{ old('nama_shift') }}"
                       class="input" placeholder="cth: Pagi" required>
                @error('nama_shift') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', '07:00') }}" class="input" required>
                </div>
                <div>
                    <label class="label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', '15:00') }}" class="input" required>
                </div>
            </div>
            <div>
                <label class="label">Toleransi Terlambat (menit)</label>
                <input type="number" name="toleransi_menit" value="{{ old('toleransi_menit', 15) }}"
                       class="input" min="0" max="120" required>
                <p class="text-xs text-ganjs-ink-muted mt-1">Karyawan dinyatakan terlambat jika masuk melewati batas ini</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan Shift</button>
                <a href="{{ route('admin.shift.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
