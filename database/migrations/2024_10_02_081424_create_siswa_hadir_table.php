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
        Schema::create('siswa_hadir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas_aktif')->nullable()->index()->references('id')->on('kelas_aktif');
            $table->foreignId('id_siswa')->nullable()->index()->references('id')->on('siswa');
            $table->string('jam_hadir')->nullable();
            $table->string('foto')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_hadir');
    }
};
