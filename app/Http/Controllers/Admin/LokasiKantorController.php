<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiKantor;
use Illuminate\Http\Request;

class LokasiKantorController extends Controller
{
    public function index()
    {
        $lokasiList = LokasiKantor::orderByDesc('is_aktif')->get();
        return view('admin.lokasi.index', compact('lokasiList'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:1000',
        ]);

        LokasiKantor::create($validated + ['is_aktif' => false]);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(LokasiKantor $lokasi)
    {
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, LokasiKantor $lokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:1000',
        ]);

        $lokasi->update($validated);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(LokasiKantor $lokasi)
    {
        $lokasi->delete();
        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }

    public function show(LokasiKantor $lokasi)
    {
        return redirect()->route('admin.lokasi.index');
    }

    public function setAktif(LokasiKantor $lokasi)
    {
        // Nonaktifkan semua lokasi, lalu aktifkan yang dipilih
        LokasiKantor::query()->update(['is_aktif' => false]);
        $lokasi->update(['is_aktif' => true]);

        return redirect()->route('admin.lokasi.index')
            ->with('success', "\"{$lokasi->nama_lokasi}\" ditetapkan sebagai lokasi aktif.");
    }
}
