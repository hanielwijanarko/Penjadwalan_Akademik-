<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Klaster sesi waktu (Pagi, Siang, Sore) beserta rentang jamnya.
 * Rentang inilah yang dipakai untuk memfilter dropdown ruangan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_waktus', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 50);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedTinyInteger('urutan')->default(1);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_waktus');
    }
};
