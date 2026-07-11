<?php

namespace App\Livewire\Absensi;

use App\Models\Absensi;
use App\Services\AbsensiService;
use App\Services\GeofenceService;
use Livewire\Component;

class AbsenKeluar extends Component
{
    public string $state = 'idle';
    public string $pesan = '';
    public ?string $waktuKeluar = null;

    public function prosesKeluar(float $latitude, float $longitude): void
    {
        $this->state = 'loading';

        $karyawan = auth()->user()->karyawan;
        if (!$karyawan) {
            $this->state = 'error';
            $this->pesan = 'Data karyawan tidak ditemukan.';
            return;
        }

        $absensiService = app(AbsensiService::class);
        $absensiHariIni = $absensiService->getAbsensiHariIni($karyawan);

        if (!$absensiHariIni) {
            $this->state = 'error';
            $this->pesan = 'Kamu belum melakukan absen masuk hari ini.';
            return;
        }

        if ($absensiHariIni->waktu_keluar) {
            $this->state = 'sudah_keluar';
            $this->waktuKeluar = $absensiHariIni->waktu_keluar->format('H:i');
            $this->pesan = 'Kamu sudah absen keluar pukul ' . $this->waktuKeluar . '.';
            return;
        }

        // Validasi geofence
        $geofenceService = app(GeofenceService::class);
        $validasi = $geofenceService->validasiRadius($latitude, $longitude, $karyawan->id_lokasi);

        if (!$validasi['valid']) {
            $this->state = 'error';
            $this->pesan = $validasi['pesan'];
            return;
        }

        $absensi = $absensiService->prosesAbsenKeluar($absensiHariIni, $latitude, $longitude);

        $this->state = 'success';
        $this->waktuKeluar = $absensi->waktu_keluar->format('H:i');
        $this->pesan = 'Absen keluar tercatat pukul ' . $this->waktuKeluar . '.';

        $this->dispatch('absensi-updated');
    }

    public function render()
    {
        $absensiHariIni = null;
        if ($karyawan = auth()->user()->karyawan) {
            $absensiHariIni = app(AbsensiService::class)->getAbsensiHariIni($karyawan);

            if ($absensiHariIni?->waktu_keluar && $this->state === 'idle') {
                $this->state = 'sudah_keluar';
                $this->waktuKeluar = $absensiHariIni->waktu_keluar->format('H:i');
                $this->pesan = 'Kamu sudah absen keluar pukul ' . $this->waktuKeluar . '.';
            }
        }

        return view('livewire.absensi.absen-keluar', [
            'absensiHariIni' => $absensiHariIni,
        ]);
    }
}
