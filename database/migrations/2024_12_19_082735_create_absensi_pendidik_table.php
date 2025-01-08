<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi_pendidik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->nullable()->index()->references('id')->on('guru');
            $table->foreignId('id_karyawan')->nullable()->index()->references('id')->on('karyawan');
            $table->foreignId('id_status_hadir')->nullable()->index()->references('id')->on('status_hadir');
            $table->date('tanggal')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->string('lokasi_keluar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_pendidik');
    }
};
