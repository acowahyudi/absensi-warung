@extends('layouts.admin')

@section('content')
<div class="max-w-lg">
    <div class="mb-5">
        <a href="{{ route('admin.karyawan.index') }}" class="btn-ghost px-0 text-ganjs-ink-muted">← Kembali</a>
        <h1 class="text-xl font-bold font-display text-ganjs-ink mt-2">Edit Data Karyawan</h1>
        <p class="text-sm text-ganjs-ink-muted">{{ $karyawan->pengguna->nama_lengkap }}</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.karyawan.update', $karyawan) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->pengguna->nama_lengkap) }}"
                       class="input" required>
                @error('nama_lengkap') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">NIK Karyawan</label>
                    <input type="text" name="nik_karyawan" value="{{ old('nik_karyawan', $karyawan->nik_karyawan) }}"
                           class="input" required>
                    @error('nik_karyawan') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="input">
                        <option value="Laki-laki" {{ $karyawan->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $karyawan->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Gaji Pokok (Rp)</label>
                    <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}"
                           class="input" min="0" step="50000" required>
                </div>
                <div>
                    <label class="label">Uang Makan/hari (Rp)</label>
                    <input type="number" name="uang_makan_per_hari" value="{{ old('uang_makan_per_hari', $karyawan->uang_makan_per_hari) }}"
                           class="input" min="0" step="5000" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $karyawan->tanggal_bergabung->format('Y-m-d')) }}"
                           class="input" required>
                </div>
                <div>
                    <label class="label">Lokasi Absen</label>
                    <select name="id_lokasi" class="input">
                        <option value="">Default (Lokasi Aktif)</option>
                        @foreach($lokasiList as $lokasi)
                            <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi', $karyawan->id_lokasi) == $lokasi->id_lokasi ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_lokasi') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-ganjs-bg rounded-xl">
                <input type="checkbox" id="is_aktif" name="is_aktif" value="1"
                       {{ $karyawan->is_aktif ? 'checked' : '' }}
                       class="w-4 h-4 rounded accent-ganjs-primary">
                <label for="is_aktif" class="text-sm font-medium text-ganjs-ink">Karyawan Aktif</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan Perubahan</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
