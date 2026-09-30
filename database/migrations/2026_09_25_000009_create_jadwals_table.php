<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel transaksi jadwal kuliah.
 *
 * Aturan bisnis:
 * - Terikat ke `semesters` (hanya satu semester aktif yang ditampilkan).
 * - `jam_selesai` dihitung otomatis dari SKS (1 SKS = 50 menit).
 * - Index komposit dipakai untuk query deteksi bentrok dosen/ruangan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->restrictOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens')->restrictOnDelete();
            $table->foreignId('ruangan_id')->constrained('ruangans')->restrictOnDelete();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('kelas', 20)->default('A');
            $table->unsignedSmallInteger('jumlah_mahasiswa')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Mempercepat query deteksi bentrok.
            $table->index(['semester_id', 'hari', 'jam_mulai', 'jam_selesai'], 'jadwals_bentrok_index');
            $table->index(['dosen_id', 'semester_id', 'hari'], 'jadwals_dosen_index');
            $table->index(['ruangan_id', 'semester_id', 'hari'], 'jadwals_ruangan_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
