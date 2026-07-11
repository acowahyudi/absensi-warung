<?php

namespace App\Services;

use App\Models\LokasiKantor;

class GeofenceService
{
    /**
     * Hitung jarak antara dua koordinat menggunakan formula Haversine.
     * Mengembalikan jarak dalam meter.
     */
    public function hitungJarakMeter(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $r = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $r * $c;
    }

    /**
     * Validasi apakah koordinat pengguna berada dalam radius lokasi kantor yang aktif.
     *
     * @return array{valid: bool, jarak: float, lokasi: LokasiKantor|null, pesan: string}
     */
    public function validasiRadius(float $latUser, float $lonUser, ?int $idLokasi = null): array
    {
        $lokasi = null;

        // Cari lokasi spesifik jika ditugaskan
        if ($idLokasi) {
            $lokasi = LokasiKantor::find($idLokasi);
        }

        // Fallback ke lokasi aktif/default
        if (!$lokasi) {
            $lokasi = LokasiKantor::aktif();
        }

        if (!$lokasi) {
            return [
                'valid' => false,
                'jarak' => 0,
                'lokasi' => null,
                'pesan' => 'Lokasi kantor belum dikonfigurasi.',
            ];
        }

        $jarak = $this->hitungJarakMeter(
            $latUser,
            $lonUser,
            (float) $lokasi->latitude,
            (float) $lokasi->longitude
        );

        $valid = $jarak <= $lokasi->radius_meter;

        return [
            'valid' => $valid,
            'jarak' => round($jarak),
            'lokasi' => $lokasi,
            'pesan' => $valid
                ? 'Kamu berada dalam area kerja.'
                : sprintf('Kamu berada %.0f meter di luar area kerja.', $jarak - $lokasi->radius_meter),
        ];
    }
}
