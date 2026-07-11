<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\LokasiKantor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawanList = Karyawan::with(['pengguna', 'lokasi'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.karyawan.index', compact('karyawanList'));
    }

    public function create()
    {
        $lokasiList = LokasiKantor::all();
        return view('admin.karyawan.create', compact('lokasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nik_karyawan' => 'required|string|unique:karyawan,nik_karyawan',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'gaji_pokok' => 'required|numeric|min:0',
            'uang_makan_per_hari' => 'required|numeric|min:0',
            'tanggal_bergabung' => 'required|date',
            'id_lokasi' => 'nullable|exists:lokasi_kantor,id_lokasi',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'karyawan',
                'id_admin' => auth()->id(),
            ]);

            Karyawan::create([
                'id_pengguna' => $user->id,
                'id_lokasi' => $validated['id_lokasi'] ?? null,
                'nik_karyawan' => $validated['nik_karyawan'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'gaji_pokok' => $validated['gaji_pokok'],
                'uang_makan_per_hari' => $validated['uang_makan_per_hari'],
                'tanggal_bergabung' => $validated['tanggal_bergabung'],
                'is_aktif' => true,
            ]);
        });

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['pengguna', 'lokasi', 'absensi' => fn($q) => $q->latest()->limit(10)]);
        return view('admin.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $karyawan->load('pengguna');
        $lokasiList = LokasiKantor::all();
        return view('admin.karyawan.edit', compact('karyawan', 'lokasiList'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_karyawan' => 'required|string|unique:karyawan,nik_karyawan,' . $karyawan->id_karyawan . ',id_karyawan',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'gaji_pokok' => 'required|numeric|min:0',
            'uang_makan_per_hari' => 'required|numeric|min:0',
            'tanggal_bergabung' => 'required|date',
            'is_aktif' => 'boolean',
            'id_lokasi' => 'nullable|exists:lokasi_kantor,id_lokasi',
        ]);

        DB::transaction(function () use ($validated, $karyawan, $request) {
            $karyawan->pengguna->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'name' => $validated['nama_lengkap'],
            ]);

            $karyawan->update([
                'id_lokasi' => $validated['id_lokasi'] ?? null,
                'nik_karyawan' => $validated['nik_karyawan'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'gaji_pokok' => $validated['gaji_pokok'],
                'uang_makan_per_hari' => $validated['uang_makan_per_hari'],
                'tanggal_bergabung' => $validated['tanggal_bergabung'],
                'is_aktif' => $request->boolean('is_aktif'),
            ]);
        });

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        // Soft delete — nonaktifkan saja
        $karyawan->update(['is_aktif' => false]);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Karyawan berhasil dinonaktifkan.');
    }
}
