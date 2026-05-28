<?php
// database/migrations/2025_01_01_000000_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     * Membuat tabel users untuk menyimpan data admin dan pegawai
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                           // ID primary key auto increment
            $table->string('name');                 // Nama lengkap user
            $table->string('email')->unique();      // Email unique untuk login
            $table->string('password');             // Password yang sudah di-hash
            $table->enum('role', ['admin', 'pegawai'])->default('pegawai'); // Role user
            $table->string('unit_kerja')->nullable(); // Unit kerja (khusus pegawai)
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif
            $table->timestamp('last_login_at')->nullable(); // Terakhir login
            $table->rememberToken();                // Token untuk "ingat saya"
            $table->timestamps();                   // created_at & updated_at
        });
    }

    /**
     * Rollback migration.
     * Hapus tabel users jika sudah ada
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
