<?php
// database/migrations/2024_01_01_000002_create_agenda_pegawai_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agenda_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained()->onDelete('cascade');
            $table->string('nama_pegawai');
            $table->string('unit_kerja');
            $table->enum('kehadiran', ['hadir', 'tidak_hadir'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_pegawai');
    }
};
