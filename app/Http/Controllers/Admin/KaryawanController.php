<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawanList = Karyawan::with('pengguna')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.karyawan.index', compact('karyawanList'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
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
        $karyawan->load(['pengguna', 'absensi' => fn($q) => $q->latest()->limit(10)]);
        return view('admin.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $karyawan->load('pengguna');
        return view('admin.karyawan.edit', compact('karyawan'));
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
        ]);

        DB::transaction(function () use ($validated, $karyawan) {
            $karyawan->pengguna->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'name' => $validated['nama_lengkap'],
            ]);

            $karyawan->update([
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
