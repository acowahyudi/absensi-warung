<?php

namespace App\Livewire\Admin;

use App\Models\Karyawan;
use App\Models\LaporanGaji as LaporanGajiModel;
use App\Services\PayrollService;
use Livewire\Component;

class LaporanGaji extends Component
{
    public string $filterBulan;
    public string $filterTahun;
    public bool $isGenerating = false;
    public string $pesanGenerate = '';

    public function mount(): void
    {
        $this->filterBulan = now()->format('m');
        $this->filterTahun = now()->format('Y');
    }

    public function generateLaporan(): void
    {
        $this->isGenerating = true;
        $this->pesanGenerate = '';

        $payrollService = app(PayrollService::class);
        $jumlah = $payrollService->generateSemuaKaryawan(
            (int) $this->filterBulan,
            (int) $this->filterTahun
        );

        $this->pesanGenerate = "Laporan berhasil digenerate untuk {$jumlah} karyawan.";
        $this->isGenerating = false;
    }

    public function render()
    {
        $laporanList = LaporanGajiModel::with(['karyawan.pengguna'])
            ->where('bulan', $this->filterBulan)
            ->where('tahun', $this->filterTahun)
            ->orderBy('id_karyawan')
            ->get();

        $totalGajiBersih = $laporanList->sum('gaji_bersih');
        $totalUangMakan = $laporanList->sum('total_uang_makan');
        $totalPotongan = $laporanList->sum('total_potongan');

        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        return view('livewire.admin.laporan-gaji', [
            'laporanList' => $laporanList,
            'totalGajiBersih' => $totalGajiBersih,
            'totalUangMakan' => $totalUangMakan,
            'totalPotongan' => $totalPotongan,
            'namaBulan' => $namaBulan[$this->filterBulan] ?? '',
        ]);
    }
}
