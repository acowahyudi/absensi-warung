<?php

namespace App\Livewire\Admin;

use App\Models\Absensi;
use App\Models\Karyawan;
use Livewire\Component;
use Livewire\WithPagination;

class RekapAbsensi extends Component
{
    use WithPagination;

    public string $filterTanggal = '';
    public string $filterKaryawan = '';
    public string $filterStatus = '';
    public int $perPage = 20;

    public function updatingFilterTanggal(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKaryawan(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Absensi::with(['karyawan.pengguna', 'jadwal.shift', 'lokasiKantor'])
            ->when($this->filterTanggal, fn($q) => $q->whereDate('waktu_masuk', $this->filterTanggal))
            ->when($this->filterStatus, fn($q) => $q->where('status_kehadiran', $this->filterStatus))
            ->when($this->filterKaryawan, function ($q) {
                $q->whereHas('karyawan.pengguna', function ($q2) {
                    $q2->where('nama_lengkap', 'like', '%' . $this->filterKaryawan . '%');
                });
            })
            ->orderByDesc('waktu_masuk');

        $karyawanList = Karyawan::with('pengguna')->where('is_aktif', true)->get();

        return view('livewire.admin.rekap-absensi', [
            'absensiList' => $query->paginate($this->perPage),
            'karyawanList' => $karyawanList,
        ]);
    }
}
