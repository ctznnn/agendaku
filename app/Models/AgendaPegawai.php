<?php
// app/Models/AgendaPegawai.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaPegawai extends Model
{
    use HasFactory;

    protected $table = 'agenda_pegawai';

    protected $fillable = [
        'agenda_id',
        'nama_pegawai',
        'unit_kerja',
        'kehadiran'
    ];

    protected $casts = [
        'kehadiran' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }

    // Accessor untuk status kehadiran badge
    public function getKehadiranBadgeColorAttribute()
    {
        return match($this->kehadiran) {
            'hadir' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'tidak_hadir' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
        };
    }
}
