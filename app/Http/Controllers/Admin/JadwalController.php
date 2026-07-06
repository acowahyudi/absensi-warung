<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKaryawan;
use App\Models\Karyawan;
use App\Models\Shift;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $jadwalList = JadwalKaryawan::with(['karyawan.pengguna', 'shift', 'absensi'])
            ->where('tanggal_kerja', $tanggal)
            ->orderBy('id_shift')
            ->get();

        $karyawanList = Karyawan::with('pengguna')->where('is_aktif', true)->get();
        $shiftList = Shift::orderBy('jam_mulai')->get();

        return view('admin.jadwal.index', compact('jadwalList', 'karyawanList', 'shiftList', 'tanggal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'id_shift' => 'required|exists:shift,id_shift',
            'tanggal_kerja' => 'required|date',
        ]);

        JadwalKaryawan::updateOrCreate(
            [
                'id_karyawan' => $validated['id_karyawan'],
                'tanggal_kerja' => $validated['tanggal_kerja'],
            ],
            [
                'id_shift' => $validated['id_shift'],
                'status_jadwal' => 'aktif',
            ]
        );

        return redirect()->route('admin.jadwal.index', ['tanggal' => $validated['tanggal_kerja']])
            ->with('success', 'Jadwal berhasil disimpan.');
    }

    public function destroy(JadwalKaryawan $jadwal)
    {
        $tanggal = $jadwal->tanggal_kerja->format('Y-m-d');
        $jadwal->update(['status_jadwal' => 'dibatalkan']);

        return redirect()->route('admin.jadwal.index', ['tanggal' => $tanggal])
            ->with('success', 'Jadwal berhasil dibatalkan.');
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'id_shift' => 'required|exists:shift,id_shift',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'karyawan_ids' => 'required|array',
            'karyawan_ids.*' => 'exists:karyawan,id_karyawan',
        ]);

        $tanggalMulai = \Carbon\Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = \Carbon\Carbon::parse($validated['tanggal_selesai']);
        $count = 0;

        while ($tanggalMulai->lte($tanggalSelesai)) {
            foreach ($validated['karyawan_ids'] as $idKaryawan) {
                JadwalKaryawan::updateOrCreate(
                    ['id_karyawan' => $idKaryawan, 'tanggal_kerja' => $tanggalMulai->format('Y-m-d')],
                    ['id_shift' => $validated['id_shift'], 'status_jadwal' => 'aktif']
                );
                $count++;
            }
            $tanggalMulai->addDay();
        }

        return redirect()->route('admin.jadwal.index')
            ->with('success', "Berhasil membuat {$count} jadwal.");
    }
}
