@extends('layouts.admin')

@section('content')
<div class="max-w-lg">
    <div class="mb-5">
        <a href="{{ route('admin.karyawan.index') }}" class="btn-ghost px-0 text-ganjs-ink-muted">← Kembali</a>
        <h1 class="text-xl font-bold font-display text-ganjs-ink mt-2">Tambah Karyawan Baru</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.karyawan.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                           class="input @error('nama_lengkap') border-ganjs-danger @enderror"
                           placeholder="Nama sesuai KTP" required>
                    @error('nama_lengkap') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="input @error('email') border-ganjs-danger @enderror"
                           placeholder="email@ganjs.com" required>
                    @error('email') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Password</label>
                    <input type="password" name="password"
                           class="input @error('password') border-ganjs-danger @enderror"
                           placeholder="Minimal 8 karakter" required>
                    @error('password') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">NIK Karyawan</label>
                        <input type="text" name="nik_karyawan" value="{{ old('nik_karyawan') }}"
                               class="input" placeholder="KRY-001" required>
                        @error('nik_karyawan') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="input">
                            <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', 2500000) }}"
                               class="input" min="0" step="50000" required>
                        @error('gaji_pokok') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Uang Makan/hari (Rp)</label>
                        <input type="number" name="uang_makan_per_hari" value="{{ old('uang_makan_per_hari', 25000) }}"
                               class="input" min="0" step="5000" required>
                        @error('uang_makan_per_hari') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="label">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', today()->format('Y-m-d')) }}"
                           class="input" required>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan Karyawan</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
