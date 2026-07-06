<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $table = 'shift';
    protected $primaryKey = 'id_shift';

    protected $fillable = [
        'nama_shift',
        'jam_mulai',
        'jam_selesai',
        'toleransi_menit',
    ];

    public function jadwalKaryawan(): HasMany
    {
        return $this->hasMany(JadwalKaryawan::class, 'id_shift', 'id_shift');
    }

    public function getJamRangeAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' – ' . substr($this->jam_selesai, 0, 5);
    }
}
