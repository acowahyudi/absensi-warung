<?php

namespace App\Livewire\Absensi;

use App\Models\Karyawan;
use App\Services\AbsensiService;
use App\Services\GeofenceService;
use Livewire\Component;

class AbsenMasuk extends Component
{
    // State: idle | loading | success | error | sudah_absen | no_jadwal
    public string $state = 'idle';
    public string $pesan = '';
    public ?string $waktuAbsen = null;
    public ?float $jarakMeter = null;

    public function getKaryawanProperty(): ?Karyawan
    {
        return auth()->user()->karyawan;
    }

    public function prosesAbsen(float $latitude, float $longitude): void
    {
        $this->state = 'loading';

        $karyawan = $this->karyawan;

        if (!$karyawan) {
            $this->state = 'error';
            $this->pesan = 'Data karyawan tidak ditemukan. Hubungi admin.';
            return;
        }

        // Cek apakah sudah absen hari ini
        $absensiService = app(AbsensiService::class);
        $absensiHariIni = $absensiService->getAbsensiHariIni($karyawan);

        if ($absensiHariIni) {
            $this->state = 'sudah_absen';
            $this->pesan = 'Kamu sudah absen masuk hari ini pukul ' .
                $absensiHariIni->waktu_masuk->format('H:i') . '.';
            $this->waktuAbsen = $absensiHariIni->waktu_masuk->format('H:i');
            return;
        }

        // Cek jadwal hari ini
        $jadwal = $absensiService->getJadwalHariIni($karyawan);
        if (!$jadwal) {
            $this->state = 'no_jadwal';
            $this->pesan = 'Kamu tidak memiliki jadwal kerja hari ini.';
            return;
        }

        // Validasi geofence di server
        $geofenceService = app(GeofenceService::class);
        $validasi = $geofenceService->validasiRadius($latitude, $longitude);
        $this->jarakMeter = $validasi['jarak'];

        if (!$validasi['valid']) {
            $this->state = 'error';
            $this->pesan = $validasi['pesan'];
            return;
        }

        // Proses absen
        $absensi = $absensiService->prosesAbsenMasuk(
            $karyawan,
            $jadwal,
            $validasi['lokasi'],
            $latitude,
            $longitude
        );

        $this->state = 'success';
        $this->waktuAbsen = $absensi->waktu_masuk->format('H:i');
        $this->pesan = 'Absen masuk tercatat pukul ' . $this->waktuAbsen . '.';

        // Emit event untuk refresh komponen lain
        $this->dispatch('absensi-updated');
    }

    public function render()
    {
        $jadwal = null;
        $absensiHariIni = null;

        if ($karyawan = $this->karyawan) {
            $absensiService = app(AbsensiService::class);
            $jadwal = $absensiService->getJadwalHariIni($karyawan);
            $absensiHariIni = $absensiService->getAbsensiHariIni($karyawan);

            if ($absensiHariIni && $this->state === 'idle') {
                $this->state = 'sudah_absen';
                $this->waktuAbsen = $absensiHariIni->waktu_masuk->format('H:i');
                $this->pesan = 'Kamu sudah absen masuk hari ini pukul ' . $this->waktuAbsen . '.';
            }
        }

        return view('livewire.absensi.absen-masuk', [
            'jadwal' => $jadwal,
            'absensiHariIni' => $absensiHariIni,
        ]);
    }
}
