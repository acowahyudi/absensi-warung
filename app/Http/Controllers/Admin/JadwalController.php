<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKaryawan;
use App\Models\Karyawan;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal terpilih, default hari ini
        $selectedDate = Carbon::parse($request->get('tanggal', today()->format('Y-m-d')));
        
        // Cari hari Senin di minggu tanggal tersebut
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek = $selectedDate->copy()->endOfWeek();

        // Buat array berisi 7 hari dalam minggu tersebut (Senin s.d. Minggu)
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        // Ambil karyawan aktif & daftar shift
        $karyawanList = Karyawan::with('pengguna')->where('is_aktif', true)->get();
        $shiftList = Shift::orderBy('jam_mulai')->get();

        // Ambil semua jadwal untuk karyawan aktif dalam rentang minggu ini
        $jadwalList = JadwalKaryawan::with('absensi')
            ->whereBetween('tanggal_kerja', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        // Mapping jadwal biar gampang dicari di view: $jadwalMap[id_karyawan][tanggal_kerja]
        $jadwalMap = [];
        foreach ($jadwalList as $j) {
            $jadwalMap[$j->id_karyawan][$j->tanggal_kerja->format('Y-m-d')] = [
                'id_shift' => $j->id_shift,
                'status_jadwal' => $j->status_jadwal,
                'has_absensi' => $j->absensi()->exists(),
                'id_jadwal' => $j->id_jadwal
            ];
        }

        return view('admin.jadwal.index', compact(
            'karyawanList',
            'shiftList',
            'days',
            'jadwalMap',
            'selectedDate',
            'startOfWeek'
        ));
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

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = Carbon::parse($validated['tanggal_selesai']);
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

    public function storeWeekly(Request $request)
    {
        $validated = $request->validate([
            'jadwal' => 'nullable|array',
            'start_date' => 'required|date',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $jadwalData = $validated['jadwal'] ?? [];

        DB::transaction(function () use ($jadwalData) {
            foreach ($jadwalData as $idKaryawan => $dates) {
                foreach ($dates as $dateStr => $idShift) {
                    // Cari jadwal yang ada untuk hari itu
                    $existingJadwal = JadwalKaryawan::with('absensi')
                        ->where('id_karyawan', $idKaryawan)
                        ->where('tanggal_kerja', $dateStr)
                        ->first();

                    // Jika sudah ada absensi, JANGAN diubah
                    if ($existingJadwal && $existingJadwal->absensi) {
                        continue;
                    }

                    if (empty($idShift)) {
                        // Jika diset "Libur (Off)", hapus jadwal yang ada
                        if ($existingJadwal) {
                            $existingJadwal->delete();
                        }
                    } else {
                        // Simpan atau update shift
                        JadwalKaryawan::updateOrCreate(
                            [
                                'id_karyawan' => $idKaryawan,
                                'tanggal_kerja' => $dateStr,
                            ],
                            [
                                'id_shift' => $idShift,
                                'status_jadwal' => 'aktif',
                            ]
                        );
                    }
                }
            }
        });

        return redirect()->route('admin.jadwal.index', ['tanggal' => $startDate->format('Y-m-d')])
            ->with('success', 'Jadwal mingguan berhasil diperbarui.');
    }
}
