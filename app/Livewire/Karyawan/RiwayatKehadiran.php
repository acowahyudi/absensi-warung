<?php

namespace App\Livewire\Karyawan;

use App\Models\Absensi;
use Livewire\Component;
use Livewire\WithPagination;

class RiwayatKehadiran extends Component
{
    use WithPagination;

    public int $perPage = 15;
    public string $filterBulan;
    public string $filterTahun;

    public function mount(): void
    {
        $this->filterBulan = now()->format('m');
        $this->filterTahun = now()->format('Y');
    }

    public function render()
    {
        $karyawan = auth()->user()->karyawan;

        $riwayat = collect();
        $summary = ['hadir' => 0, 'terlambat' => 0, 'tidak_hadir' => 0, 'uang_makan' => 0];

        if ($karyawan) {
            $query = Absensi::with('jadwal.shift')
                ->where('id_karyawan', $karyawan->id_karyawan)
                ->whereMonth('waktu_masuk', $this->filterBulan)
                ->whereYear('waktu_masuk', $this->filterTahun)
                ->orderByDesc('waktu_masuk');

            $riwayat = $query->paginate($this->perPage);

            $all = Absensi::where('id_karyawan', $karyawan->id_karyawan)
                ->whereMonth('waktu_masuk', $this->filterBulan)
                ->whereYear('waktu_masuk', $this->filterTahun)
                ->get();

            $summary = [
                'hadir' => $all->where('status_kehadiran', 'hadir')->count(),
                'terlambat' => $all->where('status_kehadiran', 'terlambat')->count(),
                'tidak_hadir' => $all->where('status_kehadiran', 'tidak_hadir')->count(),
                'uang_makan' => $all->sum('uang_makan_diterima'),
            ];
        }

        return view('livewire.karyawan.riwayat-kehadiran', [
            'riwayat' => $riwayat,
            'summary' => $summary,
        ]);
    }
}
