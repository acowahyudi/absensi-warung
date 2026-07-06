<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_karyawan',
        'id_jadwal',
        'id_lokasi',
        'waktu_masuk',
        'waktu_keluar',
        'latitude_masuk',
        'longitude_masuk',
        'latitude_keluar',
        'longitude_keluar',
        'status_kehadiran',
        'uang_makan_diterima',
        'keterangan',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'latitude_masuk' => 'decimal:7',
        'longitude_masuk' => 'decimal:7',
        'latitude_keluar' => 'decimal:7',
        'longitude_keluar' => 'decimal:7',
        'uang_makan_diterima' => 'decimal:2',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalKaryawan::class, 'id_jadwal', 'id_jadwal');
    }

    public function lokasiKantor(): BelongsTo
    {
        return $this->belongsTo(LokasiKantor::class, 'id_lokasi', 'id_lokasi');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_kehadiran) {
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'tidak_hadir' => 'Tidak Hadir',
            default => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_kehadiran) {
            'hadir' => 'green',
            'terlambat' => 'amber',
            'tidak_hadir' => 'red',
            default => 'gray',
        };
    }
}
