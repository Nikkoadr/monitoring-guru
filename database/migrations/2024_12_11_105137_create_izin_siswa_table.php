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
        Schema::create('izin_siswa', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->foreignId('id_siswa')->nullable()->index()->references('id')->on('siswa');
            $table->foreignId('id_kelas')->nullable()->index()->references('id')->on('kelas');
            $table->string('alasan')->nullable();
            $table->foreignId('id_status_izin')->nullable()->index()->references('id')->on('status_izin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_siswa');
    }
};
