<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Backup data dulu
        $agendas = DB::table('agendas')->get();

        foreach ($agendas as $agenda) {
            // Ekstrak jam saja
            $waktuMulai = $agenda->waktu_mulai;
            if (strpos($waktuMulai, ' ') !== false) {
                $parts = explode(' ', $waktuMulai);
                $waktuMulai = end($parts);
            }
            if (strlen($waktuMulai) > 8) {
                $waktuMulai = substr($waktuMulai, -8);
            }

            $waktuSelesai = null;
            if ($agenda->waktu_selesai) {
                $waktuSelesai = $agenda->waktu_selesai;
                if (strpos($waktuSelesai, ' ') !== false) {
                    $parts = explode(' ', $waktuSelesai);
                    $waktuSelesai = end($parts);
                }
                if (strlen($waktuSelesai) > 8) {
                    $waktuSelesai = substr($waktuSelesai, -8);
                }
            }

            DB::table('agendas')
                ->where('id', $agenda->id)
                ->update([
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai
                ]);
        }

        // Ubah tipe kolom menjadi TIME
        Schema::table('agendas', function (Blueprint $table) {
            $table->time('waktu_mulai')->change();
            $table->time('waktu_selesai')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('waktu_mulai')->change();
            $table->string('waktu_selesai')->nullable()->change();
        });
    }
};
