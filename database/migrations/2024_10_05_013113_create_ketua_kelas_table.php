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
        Schema::create('ketua_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->nullable()->index()->references('id')->on('siswa');
            $table->foreignId('id_kelas')->nullable()->index()->references('id')->on('kelas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketua_kelas');
    }
};
