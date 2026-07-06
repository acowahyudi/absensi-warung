<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanGaji extends Model
{
    protected $table = 'laporan_gaji';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_karyawan',
        'bulan',
        'tahun',
        'total_hadir',
        'total_terlambat',
        'total_tidak_hadir',
        'total_uang_makan',
        'total_potongan',
        'gaji_bersih',
        'generated_at',
    ];

    protected $casts = [
        'total_uang_makan' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'generated_at' => 'datetime',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return ($bulan[$this->bulan] ?? '') . ' ' . $this->tahun;
    }
}
