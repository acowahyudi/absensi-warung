<?php

namespace App\Livewire\Admin;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\JadwalKaryawan;
use Livewire\Component;

class DashboardSummary extends Component
{
    protected $listeners = ['absensi-updated' => '$refresh'];

    public function render()
    {
        $today = today();
        $absensiHariIni = Absensi::whereDate('waktu_masuk', $today)->get();

        $totalKaryawan = Karyawan::where('is_aktif', true)->count();
        $totalJadwalHariIni = JadwalKaryawan::where('tanggal_kerja', $today)
            ->where('status_jadwal', 'aktif')
            ->count();

        $stats = [
            'total_karyawan' => $totalKaryawan,
            'hadir' => $absensiHariIni->where('status_kehadiran', 'hadir')->count(),
            'terlambat' => $absensiHariIni->where('status_kehadiran', 'terlambat')->count(),
            'tidak_hadir' => $absensiHariIni->where('status_kehadiran', 'tidak_hadir')->count(),
            'belum_absen' => max(0, $totalJadwalHariIni - $absensiHariIni->count()),
        ];

        // Absensi terbaru (10 terakhir)
        $absensiTerbaru = Absensi::with(['karyawan.pengguna', 'jadwal.shift'])
            ->whereDate('waktu_masuk', $today)
            ->orderByDesc('waktu_masuk')
            ->limit(10)
            ->get();

        return view('livewire.admin.dashboard-summary', [
            'stats' => $stats,
            'absensiTerbaru' => $absensiTerbaru,
        ]);
    }
}
