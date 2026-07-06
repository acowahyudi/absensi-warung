<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Services\AbsensiService;

class DashboardController extends Controller
{
    public function index(AbsensiService $absensiService)
    {
        $karyawan = auth()->user()->karyawan;

        $jadwalHariIni = null;
        $absensiHariIni = null;
        $riwayat3Hari = collect();

        if ($karyawan) {
            $jadwalHariIni = $absensiService->getJadwalHariIni($karyawan);
            $absensiHariIni = $absensiService->getAbsensiHariIni($karyawan);

            $riwayat3Hari = \App\Models\Absensi::with('jadwal.shift')
                ->where('id_karyawan', $karyawan->id_karyawan)
                ->where('waktu_masuk', '>=', now()->subDays(3))
                ->orderByDesc('waktu_masuk')
                ->limit(3)
                ->get();
        }

        return view('karyawan.dashboard', compact(
            'karyawan',
            'jadwalHariIni',
            'absensiHariIni',
            'riwayat3Hari'
        ));
    }
}
