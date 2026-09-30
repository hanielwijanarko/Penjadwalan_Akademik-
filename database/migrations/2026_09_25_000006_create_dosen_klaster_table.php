<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pivot klasterisasi dosen (many-to-many).
 * Satu dosen boleh berada di lebih dari satu klaster keahlian.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_klaster', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->foreignId('klaster_id')->constrained('klasters')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dosen_id', 'klaster_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_klaster');
    }
};
