<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Agenda extends Model
{
    protected $table = 'agendas';

    protected $fillable = [
        'judul', 'deskripsi', 'tanggal', 'waktu_mulai', 'waktu_selesai',
        'tempat', 'penyelenggara', 'kategori', 'status', 'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pegawai(): HasMany
    {
        return $this->hasMany(AgendaPegawai::class, 'agenda_id');
    }

    /**
     * Accessor untuk waktu mulai (format H:i)
     */
    public function getWaktuMulaiFormattedAttribute()
    {
        if (empty($this->waktu_mulai)) {
            return '--:--';
        }

        try {
            // Jika sudah dalam format H:i:s
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $this->waktu_mulai)) {
                return date('H:i', strtotime($this->waktu_mulai));
            }
            // Jika dalam format datetime
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $this->waktu_mulai)) {
                return date('H:i', strtotime($this->waktu_mulai));
            }
            // Jika sudah dalam format H:i
            if (preg_match('/^\d{2}:\d{2}$/', $this->waktu_mulai)) {
                return $this->waktu_mulai;
            }
            return '--:--';
        } catch (\Exception $e) {
            return '--:--';
        }
    }

    /**
     * Accessor untuk waktu selesai (format H:i)
     */
    public function getWaktuSelesaiFormattedAttribute()
    {
        if (empty($this->waktu_selesai)) {
            return null;
        }

        try {
            // Jika sudah dalam format H:i:s
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $this->waktu_selesai)) {
                return date('H:i', strtotime($this->waktu_selesai));
            }
            // Jika dalam format datetime
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $this->waktu_selesai)) {
                return date('H:i', strtotime($this->waktu_selesai));
            }
            // Jika sudah dalam format H:i
            if (preg_match('/^\d{2}:\d{2}$/', $this->waktu_selesai)) {
                return $this->waktu_selesai;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Update status agenda berdasarkan tanggal dan waktu
     */
    public function updateStatusOtomatis()
    {
        try {
            $now = Carbon::now();
            $tanggalAgenda = Carbon::parse($this->tanggal);

            // Parse waktu mulai dengan aman
            $waktuMulaiStr = $this->getWaktuMulaiFormattedAttribute();
            if ($waktuMulaiStr == '--:--') {
                return;
            }

            $waktuMulai = Carbon::parse($this->tanggal . ' ' . $waktuMulaiStr);

            if ($this->status == 'batal') {
                return;
            }

            if ($tanggalAgenda->isPast()) {
                if ($this->status != 'selesai') {
                    $this->update(['status' => 'selesai']);
                }
                return;
            }

            if ($tanggalAgenda->isToday()) {
                if ($now->greaterThanOrEqualTo($waktuMulai)) {
                    if ($this->status != 'berlangsung') {
                        $this->update(['status' => 'berlangsung']);
                    }
                } else {
                    if ($this->status != 'direncanakan') {
                        $this->update(['status' => 'direncanakan']);
                    }
                }
                return;
            }

            if ($tanggalAgenda->isFuture()) {
                if ($this->status != 'direncanakan') {
                    $this->update(['status' => 'direncanakan']);
                }
            }
        } catch (\Exception $e) {
            // Skip jika error parsing waktu
            return;
        }
    }

    /**
     * Update semua agenda
     */
    public static function updateSemuaStatus()
    {
        $agendas = self::whereIn('status', ['direncanakan', 'berlangsung'])->get();
        foreach ($agendas as $agenda) {
            $agenda->updateStatusOtomatis();
        }
    }
}
