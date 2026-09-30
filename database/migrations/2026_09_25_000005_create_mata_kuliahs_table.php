<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Master data mata kuliah.
 *
 * `sks` dipakai sebagai dasar perhitungan durasi (1 SKS = 50 menit),
 * `klaster_id` dipakai sebagai dasar filtering dosen pengampu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->unsignedTinyInteger('sks')->default(2);
            $table->text('deskripsi')->nullable();
            $table->enum('sifat', ['wajib', 'pilihan'])->default('wajib');
            $table->foreignId('klaster_id')->constrained('klasters')->restrictOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
