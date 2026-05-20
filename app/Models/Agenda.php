<?php
// app/Models/Agenda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'penyelenggara',
        'kategori',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function pegawai()
    {
        return $this->hasMany(AgendaPegawai::class, 'agenda_id');
    }

    // Accessor untuk tanggal formatted
    public function getTanggalFormattedAttribute()
    {
        return date('d/m/Y', strtotime($this->tanggal));
    }

    // Accessor untuk waktu mulai formatted
    public function getWaktuMulaiFormattedAttribute()
    {
        return date('H:i', strtotime($this->waktu_mulai));
    }

    // Accessor untuk status badge color
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'direncanakan' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            'berlangsung' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            'selesai' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
            'dibatalkan' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            default => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
        };
    }
}
