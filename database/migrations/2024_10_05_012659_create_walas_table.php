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
        Schema::create('walas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->nullable()->index()->references('id')->on('guru');
            $table->foreignId('id_mapel')->nullable()->index()->references('id')->on('mapel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walas');
    }
};
