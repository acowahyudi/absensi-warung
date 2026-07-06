<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JadwalKaryawan extends Model
{
    protected $table = 'jadwal_karyawan';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_karyawan',
        'id_shift',
        'tanggal_kerja',
        'status_jadwal',
    ];

    protected $casts = [
        'tanggal_kerja' => 'date',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'id_shift', 'id_shift');
    }

    public function absensi(): HasOne
    {
        return $this->hasOne(Absensi::class, 'id_jadwal', 'id_jadwal');
    }
}
