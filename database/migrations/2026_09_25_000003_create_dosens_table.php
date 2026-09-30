<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Profil dosen. Terhubung opsional ke `users` agar dosen bisa login
 * ke panel Dosen memakai akun kampusnya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nidn', 20)->unique();
            $table->string('nama', 120);
            $table->string('email')->unique();
            $table->string('no_hp', 20)->nullable();
            $table->string('jabatan_fungsional', 60)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
