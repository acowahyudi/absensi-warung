<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreignId('id_lokasi')
                ->nullable()
                ->after('id_pengguna')
                ->constrained('lokasi_kantor', 'id_lokasi')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['id_lokasi']);
            $table->dropColumn('id_lokasi');
        });
    }
};
