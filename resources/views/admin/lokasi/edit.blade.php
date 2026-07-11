@extends('layouts.admin')

@section('content')
<div class="max-w-md">
    <div class="mb-5">
        <a href="{{ route('admin.lokasi.index') }}" class="btn-ghost px-0">← Kembali</a>
        <h1 class="text-xl font-bold font-display text-ganjs-ink mt-2">
            {{ isset($lokasi) ? 'Edit Lokasi' : 'Tambah Lokasi Kantor' }}
        </h1>
    </div>
    <div class="card">
        <form method="POST"
              action="{{ isset($lokasi) ? route('admin.lokasi.update', $lokasi) : route('admin.lokasi.store') }}"
              class="space-y-4">
            @csrf
            @if(isset($lokasi)) @method('PUT') @endif

            <div>
                <label class="label">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $lokasi->nama_lokasi ?? '') }}"
                       class="input" placeholder='cth: Ayam Bebek GANJ'"'"'S "Cak Ali"' required>
                @error('nama_lokasi') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Latitude</label>
                    <input type="number" name="latitude" step="any"
                           value="{{ old('latitude', $lokasi->latitude ?? -7.2574719) }}"
                           class="input font-mono" required>
                    @error('latitude') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Longitude</label>
                    <input type="number" name="longitude" step="any"
                           value="{{ old('longitude', $lokasi->longitude ?? 112.7520883) }}"
                           class="input font-mono" required>
                    @error('longitude') <p class="text-xs text-ganjs-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Radius Geofence (meter)</label>
                <input type="number" name="radius_meter" value="{{ old('radius_meter', $lokasi->radius_meter ?? 100) }}"
                       class="input" min="10" max="1000" required>
                <p class="text-xs text-ganjs-ink-muted mt-1">Karyawan harus berada dalam radius ini untuk absen</p>
            </div>

            <div class="bg-ganjs-warning-light border border-ganjs-warning/30 rounded-xl p-3">
                <p class="text-xs font-semibold text-ganjs-warning">💡 Tips</p>
                <p class="text-xs text-ganjs-warning mt-1">
                    Dapatkan koordinat dari Google Maps: klik kanan lokasi → "Koordinat ini" → copy angkanya.
                </p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan</button>
                <a href="{{ route('admin.lokasi.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
