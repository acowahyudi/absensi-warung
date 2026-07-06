<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\LaporanGaji;

class PayrollService
{
    /**
     * Generate atau refresh laporan gaji bulanan untuk seorang karyawan.
     */
    public function generateLaporanBulanan(
        Karyawan $karyawan,
        int $bulan,
        int $tahun
    ): LaporanGaji {
        // Ambil semua absensi dalam bulan tersebut
        $absensi = Absensi::where('id_karyawan', $karyawan->id_karyawan)
            ->whereMonth('waktu_masuk', $bulan)
            ->whereYear('waktu_masuk', $tahun)
            ->get();

        $totalHadir = $absensi->where('status_kehadiran', 'hadir')->count();
        $totalTerlambat = $absensi->where('status_kehadiran', 'terlambat')->count();
        $totalTidakHadir = $absensi->where('status_kehadiran', 'tidak_hadir')->count();

        $totalUangMakan = $absensi->sum('uang_makan_diterima');

        // Potongan: setiap hari tidak hadir dipotong proporsional dari gaji pokok
        // Asumsi: 1 hari tidak hadir = gaji_pokok / 26 hari kerja
        $hariKerjaBulanan = 26;
        $potonganPerHari = $totalTidakHadir > 0
            ? ($karyawan->gaji_pokok / $hariKerjaBulanan) * $totalTidakHadir
            : 0;

        $gajiBersih = $karyawan->gaji_pokok - $potonganPerHari + $totalUangMakan;

        return LaporanGaji::updateOrCreate(
            [
                'id_karyawan' => $karyawan->id_karyawan,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            [
                'total_hadir' => $totalHadir,
                'total_terlambat' => $totalTerlambat,
                'total_tidak_hadir' => $totalTidakHadir,
                'total_uang_makan' => $totalUangMakan,
                'total_potongan' => $potonganPerHari,
                'gaji_bersih' => max(0, $gajiBersih),
                'generated_at' => now(),
            ]
        );
    }

    /**
     * Generate laporan untuk semua karyawan aktif.
     */
    public function generateSemuaKaryawan(int $bulan, int $tahun): int
    {
        $karyawanAktif = Karyawan::where('is_aktif', true)->get();

        foreach ($karyawanAktif as $karyawan) {
            $this->generateLaporanBulanan($karyawan, $bulan, $tahun);
        }

        return $karyawanAktif->count();
    }
}
