<?php

namespace Database\Seeders;

use App\Models\LokasiKantor;
use App\Models\Shift;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== LOKASI KANTOR =====
        $lokasi = LokasiKantor::create([
            'nama_lokasi' => 'Ayam Bebek GANJ\'S "Cak Ali"',
            // Koordinat dummy — ubah via dashboard Admin setelah deployment
            'latitude' => -7.2574719,
            'longitude' => 112.7520883,
            'radius_meter' => 100,
            'is_aktif' => true,
        ]);

        // ===== SHIFT =====
        Shift::insert([
            [
                'nama_shift' => 'Pagi',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '15:00:00',
                'toleransi_menit' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'Siang',
                'jam_mulai' => '12:00:00',
                'jam_selesai' => '20:00:00',
                'toleransi_menit' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'Malam',
                'jam_mulai' => '20:00:00',
                'jam_selesai' => '04:00:00',
                'toleransi_menit' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ===== ADMIN =====
        $admin = User::create([
            'name' => 'Admin',
            'nama_lengkap' => 'Administrator GANJ\'S',
            'email' => 'admin@ganjs.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // ===== KARYAWAN DEMO =====
        $userKaryawan1 = User::create([
            'name' => 'Rina Sari',
            'nama_lengkap' => 'Rina Sari',
            'email' => 'rina@ganjs.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'id_admin' => $admin->id,
        ]);

        Karyawan::create([
            'id_pengguna' => $userKaryawan1->id,
            'nik_karyawan' => 'KRY-001',
            'jenis_kelamin' => 'Perempuan',
            'gaji_pokok' => 2500000,
            'uang_makan_per_hari' => 25000,
            'tanggal_bergabung' => '2024-01-15',
            'is_aktif' => true,
        ]);

        $userKaryawan2 = User::create([
            'name' => 'Budi Santoso',
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@ganjs.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'id_admin' => $admin->id,
        ]);

        Karyawan::create([
            'id_pengguna' => $userKaryawan2->id,
            'nik_karyawan' => 'KRY-002',
            'jenis_kelamin' => 'Laki-laki',
            'gaji_pokok' => 2800000,
            'uang_makan_per_hari' => 25000,
            'tanggal_bergabung' => '2023-06-01',
            'is_aktif' => true,
        ]);
    }
}
