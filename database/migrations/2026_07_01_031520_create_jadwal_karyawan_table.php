<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_karyawan', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->unsignedBigInteger('id_karyawan');
            $table->unsignedBigInteger('id_shift');
            $table->date('tanggal_kerja');
            $table->enum('status_jadwal', ['aktif', 'dibatalkan'])->default('aktif');
            $table->timestamps();

            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
            $table->foreign('id_shift')->references('id_shift')->on('shift')->onDelete('cascade');
            $table->unique(['id_karyawan', 'tanggal_kerja']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_karyawan');
    }
};
