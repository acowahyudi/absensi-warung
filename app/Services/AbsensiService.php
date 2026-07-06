<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\JadwalKaryawan;
use App\Models\Karyawan;
use App\Models\LokasiKantor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiService
{
    public function __construct(protected GeofenceService $geofenceService) {}

    /**
     * Tentukan status kehadiran berdasarkan waktu masuk vs jadwal shift.
     */
    public function tentukanStatusKehadiran(
        Carbon $waktuMasuk,
        JadwalKaryawan $jadwal
    ): string {
        $shift = $jadwal->shift;

        // Batas waktu toleransi: jam_mulai + toleransi_menit
        $batasWaktu = Carbon::parse(
            $waktuMasuk->format('Y-m-d') . ' ' . $shift->jam_mulai
        )->addMinutes($shift->toleransi_menit);

        return $waktuMasuk->lte($batasWaktu) ? 'hadir' : 'terlambat';
    }

    /**
     * Hitung uang makan yang diterima: penuh jika hadir, 0 jika terlambat/tidak hadir.
     */
    public function hitungUangMakan(string $status, Karyawan $karyawan): float
    {
        return $status === 'hadir' ? (float) $karyawan->uang_makan_per_hari : 0.0;
    }

    /**
     * Proses absen masuk.
     */
    public function prosesAbsenMasuk(
        Karyawan $karyawan,
        JadwalKaryawan $jadwal,
        LokasiKantor $lokasi,
        float $latitude,
        float $longitude
    ): Absensi {
        $waktuMasuk = now();
        $status = $this->tentukanStatusKehadiran($waktuMasuk, $jadwal);
        $uangMakan = $this->hitungUangMakan($status, $karyawan);

        return DB::transaction(function () use (
            $karyawan, $jadwal, $lokasi,
            $latitude, $longitude,
            $waktuMasuk, $status, $uangMakan
        ) {
            return Absensi::create([
                'id_karyawan' => $karyawan->id_karyawan,
                'id_jadwal' => $jadwal->id_jadwal,
                'id_lokasi' => $lokasi->id_lokasi,
                'waktu_masuk' => $waktuMasuk,
                'latitude_masuk' => $latitude,
                'longitude_masuk' => $longitude,
                'status_kehadiran' => $status,
                'uang_makan_diterima' => $uangMakan,
            ]);
        });
    }

    /**
     * Proses absen keluar.
     */
    public function prosesAbsenKeluar(
        Absensi $absensi,
        float $latitude,
        float $longitude
    ): Absensi {
        $absensi->update([
            'waktu_keluar' => now(),
            'latitude_keluar' => $latitude,
            'longitude_keluar' => $longitude,
        ]);

        return $absensi;
    }

    /**
     * Dapatkan jadwal hari ini untuk karyawan.
     */
    public function getJadwalHariIni(Karyawan $karyawan): ?JadwalKaryawan
    {
        return JadwalKaryawan::with('shift')
            ->where('id_karyawan', $karyawan->id_karyawan)
            ->where('tanggal_kerja', today())
            ->where('status_jadwal', 'aktif')
            ->first();
    }

    /**
     * Cek apakah karyawan sudah absen masuk hari ini.
     */
    public function getAbsensiHariIni(Karyawan $karyawan): ?Absensi
    {
        return Absensi::where('id_karyawan', $karyawan->id_karyawan)
            ->whereDate('waktu_masuk', today())
            ->first();
    }
}
