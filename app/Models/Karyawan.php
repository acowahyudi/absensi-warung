<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'id_pengguna',
        'id_lokasi',
        'nik_karyawan',
        'jenis_kelamin',
        'gaji_pokok',
        'uang_makan_per_hari',
        'tanggal_bergabung',
        'is_aktif',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'uang_makan_per_hari' => 'decimal:2',
        'tanggal_bergabung' => 'date',
        'is_aktif' => 'boolean',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function jadwalKaryawan(): HasMany
    {
        return $this->hasMany(JadwalKaryawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_karyawan', 'id_karyawan');
    }

    public function laporanGaji(): HasMany
    {
        return $this->hasMany(LaporanGaji::class, 'id_karyawan', 'id_karyawan');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(LokasiKantor::class, 'id_lokasi', 'id_lokasi');
    }

    public function getNamaAttribute(): string
    {
        return $this->pengguna->nama_lengkap ?? '';
    }
}
