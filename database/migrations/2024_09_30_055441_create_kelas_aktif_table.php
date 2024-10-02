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
        Schema::create('kelas_aktif', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('kelas')->nullable();
            $table->foreignId('id_mapel')->nullable()->index()->references('id')->on('mapel');
            $table->string('jam_ke')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('jam_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->string('jam_keluar')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_aktif');
    }
};
