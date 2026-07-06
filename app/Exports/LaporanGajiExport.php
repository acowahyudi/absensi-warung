<?php

namespace App\Exports;

use App\Models\LaporanGaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanGajiExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function __construct(
        private int $bulan,
        private int $tahun
    ) {}

    public function collection()
    {
        return LaporanGaji::with('karyawan.pengguna')
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->get()
            ->map(function ($laporan) {
                return [
                    'NIK' => $laporan->karyawan->nik_karyawan,
                    'Nama Karyawan' => $laporan->karyawan->pengguna->nama_lengkap,
                    'Hadir' => $laporan->total_hadir,
                    'Terlambat' => $laporan->total_terlambat,
                    'Tidak Hadir' => $laporan->total_tidak_hadir,
                    'Uang Makan' => (float) $laporan->total_uang_makan,
                    'Potongan' => (float) $laporan->total_potongan,
                    'Gaji Pokok' => (float) $laporan->karyawan->gaji_pokok,
                    'Gaji Bersih' => (float) $laporan->gaji_bersih,
                    'Dibuat' => $laporan->generated_at?->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Hari Hadir',
            'Hari Terlambat',
            'Hari Tidak Hadir',
            'Total Uang Makan (Rp)',
            'Total Potongan (Rp)',
            'Gaji Pokok (Rp)',
            'Gaji Bersih (Rp)',
            'Dibuat Pada',
        ];
    }

    public function title(): string
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return 'Laporan ' . ($namaBulan[$this->bulan] ?? $this->bulan) . ' ' . $this->tahun;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
