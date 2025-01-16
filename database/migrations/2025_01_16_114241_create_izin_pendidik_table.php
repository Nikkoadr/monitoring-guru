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
        Schema::create('izin_pendidik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->nullable()->index()->references('id')->on('guru');
            $table->foreignId('id_karyawan')->nullable()->index()->references('id')->on('karyawan');
            $table->date('tanggal')->nullable();
            $table->time('jam_izin')->nullable();
            $table->string('alasan')->nullable();
            $table->string('file')->nullable();
            $table->foreignId('id_status_izin')->nullable()->index()->references('id')->on('status_izin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_pendidik');
    }
};
