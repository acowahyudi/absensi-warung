<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\LaporanGaji;
use App\Services\PayrollService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanGajiExport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('m'));
        $tahun = $request->get('tahun', now()->format('Y'));

        $laporanList = LaporanGaji::with('karyawan.pengguna')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        return view('admin.laporan.index', compact('laporanList', 'bulan', 'tahun'));
    }

    public function generate(Request $request, PayrollService $payrollService)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $jumlah = $payrollService->generateSemuaKaryawan($bulan, $tahun);

        return redirect()->route('admin.laporan.index', compact('bulan', 'tahun'))
            ->with('success', "Laporan berhasil digenerate untuk {$jumlah} karyawan.");
    }

    public function exportExcel(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $filename = 'laporan-gaji-' . ($namaBulan[$bulan] ?? $bulan) . '-' . $tahun . '.xlsx';

        return Excel::download(new LaporanGajiExport($bulan, $tahun), $filename);
    }
}
