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
        Schema::create('kbm', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->foreignId('id_guru')->nullable()->index()->references('id')->on('guru');
            $table->foreignId('id_mapel')->nullable()->index()->references('id')->on('mapel');
            $table->foreignId('id_kelas')->nullable()->index()->references('id')->on('kelas');
            $table->string('jam_ke')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kbm');
    }
};
