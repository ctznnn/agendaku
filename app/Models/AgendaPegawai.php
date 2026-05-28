<?php
// app/Models/AgendaPegawai.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaPegawai extends Model
{
    // Tentukan nama tabel secara eksplisit (opsional, karena Laravel akan otomatis menggunakan plural dari model name)
    // Tapi karena model name "AgendaPegawai" akan di-plural jadi "agenda_pegawais",
    // sementara tabel Anda "agenda_pegawai", maka perlu di-set manual
    protected $table = 'agenda_pegawai';

    protected $fillable = [
        'agenda_id',
        'nama_pegawai',
        'unit_kerja',
        'kehadiran'
    ];

    protected $casts = [
        'kehadiran' => 'string',
    ];

    // Relasi ke Agenda
    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    // Accessor untuk teks kehadiran
    public function getKehadiranTextAttribute()
    {
        return match($this->kehadiran) {
            'hadir' => 'Hadir',
            'tidak_hadir' => 'Tidak Hadir',
            default => 'Belum Absen',
        };
    }

    // Accessor untuk warna badge
    public function getKehadiranBadgeColorAttribute()
    {
        return match($this->kehadiran) {
            'hadir' => 'success',
            'tidak_hadir' => 'danger',
            default => 'secondary',
        };
    }
}
