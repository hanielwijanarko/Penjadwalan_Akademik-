<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Master data klaster keahlian / rumpun ilmu.
 *
 * Digunakan bersama oleh `dosens` (keahlian) dan `mata_kuliahs` (kebutuhan
 * pengampu) sehingga aturan bisnis "dosen hanya boleh mengampu matkul pada
 * klaster yang sama" dapat ditegakkan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasters', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasters');
    }
};
