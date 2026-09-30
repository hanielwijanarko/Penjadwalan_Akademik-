<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Master data ruangan kelas beserta detail fasilitas dan lokasi gedung.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->unsignedSmallInteger('kapasitas')->default(30);
            $table->string('gedung', 60);
            $table->string('lantai', 20)->nullable();
            $table->json('fasilitas')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
