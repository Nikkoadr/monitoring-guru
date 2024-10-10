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
        Schema::create('absensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->foreignId('id_kbm')->nullable()->index()->references('id')->on('kbm');
            $table->foreignId('id_siswa')->nullable()->index()->references('id')->on('siswa');
            $table->foreignId('id_status_hadir')->nullable()->index()->references('id')->on('status_hadir');
            $table->string('jam_hadir')->nullable();
            $table->string('foto')->nullable();
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_siswa');
    }
};
