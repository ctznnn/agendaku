<?php
// database/migrations/2025_01_02_000000_add_fields_to_users.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     * Menambahkan kolom untuk fitur ganti email
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable(); // Waktu verifikasi email
            $table->string('new_email')->nullable();            // Email baru yang diajukan
            $table->string('new_email_token')->nullable();      // Token verifikasi email baru
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'new_email', 'new_email_token']);
        });
    }
};
