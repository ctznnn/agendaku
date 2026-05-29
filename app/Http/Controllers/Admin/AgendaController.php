<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AgendaPegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Update semua status agenda otomatis
     */
    private function updateAllStatuses()
    {
        $agendas = Agenda::all();
        foreach ($agendas as $agenda) {
            $this->updateAgendaStatus($agenda);
        }
    }

    /**
     * Update status single agenda
     */
    private function updateAgendaStatus($agenda)
    {
        try {
            $now = Carbon::now();
            $tanggalAgenda = Carbon::parse($agenda->tanggal);

            $waktuMulaiStr = $this->cleanTimeString($agenda->waktu_mulai);
            if (!$waktuMulaiStr) {
                return;
            }

            $waktuMulai = Carbon::parse($agenda->tanggal . ' ' . $waktuMulaiStr);

            if ($agenda->status == 'batal') {
                return;
            }

            $newStatus = $agenda->status;

            if ($tanggalAgenda->lt($now->startOfDay())) {
                $newStatus = 'selesai';
            } elseif ($tanggalAgenda->isToday()) {
                if ($now->greaterThanOrEqualTo($waktuMulai)) {
                    $newStatus = 'berlangsung';
                } else {
                    $newStatus = 'direncanakan';
                }
            } elseif ($tanggalAgenda->gt($now->startOfDay())) {
                $newStatus = 'direncanakan';
            }

            if ($agenda->status != $newStatus) {
                $agenda->update(['status' => $newStatus]);
            }

        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * Bersihkan string waktu
     */
    private function cleanTimeString($time)
    {
        if (empty($time)) {
            return null;
        }

        $waktu = $time;

        if (strpos($waktu, ' ') !== false) {
            $parts = explode(' ', $waktu);
            $waktu = end($parts);
        }

        if (strlen($waktu) > 8) {
            $waktu = substr($waktu, -8);
        }

        if (preg_match('/^\d{2}:\d{2}$/', $waktu)) {
            $waktu = $waktu . ':00';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $waktu)) {
            return $waktu;
        }

        return null;
    }

    /**
     * INDEX - Daftar agenda (diurutkan berdasarkan jam mulai)
     */
    public function index(Request $request)
    {
        $this->updateAllStatuses();

        $query = Agenda::with('pegawai');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('tempat', 'like', '%' . $search . '%')
                    ->orWhere('penyelenggara', 'like', '%' . $search . '%');
            });
        }

        // Urutkan berdasarkan jam mulai dari yang paling kecil
        $agendas = $query->orderByRaw('CAST(waktu_mulai AS TIME) ASC')
            ->orderBy('tanggal', 'asc')
            ->paginate(10);

        return view('admin.agendas.index', compact('agendas'));
    }

    /**
     * CREATE - Form tambah agenda
     */
    public function create()
    {
        $pegawaiList = User::where('role', 'pegawai')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.agendas.create', compact('pegawaiList'));
    }

    /**
     * STORE - Simpan agenda baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'tempat' => 'required|string|max:255',
            'pegawai_ids' => 'required|array|min:1',
            'pegawai_ids.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $waktuMulai = date('H:i:s', strtotime($request->waktu_mulai));
            $waktuSelesai = $request->waktu_selesai ? date('H:i:s', strtotime($request->waktu_selesai)) : null;

            $now = Carbon::now();
            $tanggalAgenda = Carbon::parse($request->tanggal);
            $waktuMulaiCarbon = Carbon::parse($request->tanggal . ' ' . $waktuMulai);

            if ($tanggalAgenda->lt($now->startOfDay())) {
                $status = 'selesai';
            } elseif ($tanggalAgenda->isToday() && $now->greaterThanOrEqualTo($waktuMulaiCarbon)) {
                $status = 'berlangsung';
            } else {
                $status = 'direncanakan';
            }

            $agenda = Agenda::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'tempat' => $request->tempat,
                'penyelenggara' => $request->penyelenggara,
                'kategori' => $request->kategori,
                'status' => $status,
                'catatan' => $request->catatan
            ]);

            $pegawaiTerpilih = User::whereIn('id', $request->pegawai_ids)->get();
            foreach ($pegawaiTerpilih as $pegawai) {
                AgendaPegawai::create([
                    'agenda_id' => $agenda->id,
                    'nama_pegawai' => $pegawai->name,
                    'unit_kerja' => $pegawai->unit_kerja,
                    'kehadiran' => null
                ]);
            }

            DB::commit();
            return redirect()->route('admin.agendas.index')
                ->with('success', 'Agenda "' . $agenda->judul . '" berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat agenda: ' . $e->getMessage());
        }
    }

    /**
     * SHOW - Detail agenda
     */
    public function show($id)
    {
        $agenda = Agenda::with('pegawai')->findOrFail($id);
        $this->updateAgendaStatus($agenda);
        $agenda->refresh();

        return view('admin.agendas.show', compact('agenda'));
    }

    /**
     * EDIT - Form edit agenda
     */
    public function edit($id)
    {
        $agenda = Agenda::with('pegawai')->findOrFail($id);
        $this->updateAgendaStatus($agenda);
        $agenda->refresh();

        $pegawaiList = User::where('role', 'pegawai')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        $selectedPegawai = $agenda->pegawai->pluck('id')->toArray();

        return view('admin.agendas.edit', compact('agenda', 'pegawaiList', 'selectedPegawai'));
    }

    /**
     * UPDATE - Update agenda
     */
    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'tempat' => 'required|string|max:255',
            'pegawai_ids' => 'required|array|min:1',
            'pegawai_ids.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $waktuMulai = date('H:i:s', strtotime($request->waktu_mulai));
            $waktuSelesai = $request->waktu_selesai ? date('H:i:s', strtotime($request->waktu_selesai)) : null;

            $now = Carbon::now();
            $tanggalAgenda = Carbon::parse($request->tanggal);
            $waktuMulaiCarbon = Carbon::parse($request->tanggal . ' ' . $waktuMulai);

            if ($tanggalAgenda->lt($now->startOfDay())) {
                $status = 'selesai';
            } elseif ($tanggalAgenda->isToday() && $now->greaterThanOrEqualTo($waktuMulaiCarbon)) {
                $status = 'berlangsung';
            } else {
                $status = 'direncanakan';
            }

            $agenda->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'tempat' => $request->tempat,
                'penyelenggara' => $request->penyelenggara,
                'kategori' => $request->kategori,
                'status' => $status,
                'catatan' => $request->catatan
            ]);

            $agenda->pegawai()->delete();

            $pegawaiTerpilih = User::whereIn('id', $request->pegawai_ids)->get();
            foreach ($pegawaiTerpilih as $pegawai) {
                AgendaPegawai::create([
                    'agenda_id' => $agenda->id,
                    'nama_pegawai' => $pegawai->name,
                    'unit_kerja' => $pegawai->unit_kerja,
                    'kehadiran' => null
                ]);
            }

            DB::commit();
            return redirect()->route('admin.agendas.index')
                ->with('success', 'Agenda "' . $agenda->judul . '" berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui agenda: ' . $e->getMessage());
        }
    }

    /**
     * DESTROY - Hapus agenda
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $judul = $agenda->judul;
            $agenda->delete();

            return redirect()->route('admin.agendas.index')
                ->with('success', 'Agenda "' . $judul . '" berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus agenda: ' . $e->getMessage());
        }
    }

    /**
     * SHARE - Share undangan single agenda
     */
    public function share($id)
    {
        try {
            $agenda = Agenda::with('pegawai')->findOrFail($id);

            $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                     'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
                     'Saturday' => 'Sabtu'];

            $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            $tanggalFormatted = $hari[date('l', strtotime($agenda->tanggal))] . ', ' .
                                date('j', strtotime($agenda->tanggal)) . ' ' .
                                $bulan[date('n', strtotime($agenda->tanggal))] . ' ' .
                                date('Y', strtotime($agenda->tanggal));

            $waktuMulai = $this->formatWaktu($agenda->waktu_mulai);
            $waktuMulaiDisplay = date('H.i', strtotime($waktuMulai));

            $waktuSelesai = $agenda->waktu_selesai ? $this->formatWaktu($agenda->waktu_selesai) : null;
            $waktuSelesaiDisplay = $waktuSelesai ? date('H.i', strtotime($waktuSelesai)) : null;

            $teks = "*AGENDA LURAH & PAMONG KALURAHAN TRIRENGGO*\n";

            if ($agenda->penyelenggara) {
                $teks .= "Surat dari : *" . $agenda->penyelenggara . "*\n";
            }

            $teks .= $tanggalFormatted . "\n\n";

            if ($waktuSelesaiDisplay) {
                $teks .= "Pukul : *" . $waktuMulaiDisplay . " WIB - " . $waktuSelesaiDisplay . " WIB*\n";
            } else {
                $teks .= "Pukul : *" . $waktuMulaiDisplay . " WIB*\n";
            }

            $teks .= "Agenda : *" . $agenda->judul . "*\n";
            $teks .= "di : " . $agenda->tempat . "\n\n";

            $teks .= "Hadir : \n";
            $pegawaiSorted = $agenda->pegawai->sortBy('nama_pegawai');
            foreach ($pegawaiSorted as $pegawai) {
                $teks .= "- " . $pegawai->nama_pegawai;
                if ($pegawai->unit_kerja) {
                    $teks .= " (" . $pegawai->unit_kerja . ")";
                }
                $teks .= "\n";
            }

            $teks = rtrim($teks);

            return response()->json([
                'success' => true,
                'text' => $teks
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
     */
    /**
 * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
 * Jika tanggal sama, header hanya muncul sekali, tanpa separator
 */
/**
 * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
 * Pakai separator antar agenda, baik tanggal sama maupun berbeda
 */
  /**
 * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
 * Jika tanggal sama, header hanya sekali
 * Urutan: Agenda dulu, baru Pukul
 */
   /**
 * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
 * Format: Header -> Surat dari -> Agenda -> di -> Pukul -> Hadir
 */
/**
 * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
 * Format: Header -> Surat dari -> Agenda -> di -> Pukul -> Hadir
 * Jika tanggal sama, header hanya sekali
 */
  /**
 * BULK SHARE - Share multiple agenda (urut berdasarkan jam terkecil)
 * Format: Header -> Surat dari -> Agenda -> di -> Pukul -> Hadir (dengan nomor urut)
 * Urutan: Lurah di paling atas, baru pegawai lainnya
 */
public function bulkShare(Request $request)
{
    try {
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada agenda yang dipilih'
            ], 400);
        }

        // Urutkan berdasarkan tanggal dan jam mulai
        $agendas = Agenda::with('pegawai')
            ->whereIn('id', $ids)
            ->orderBy('tanggal', 'asc')
            ->orderByRaw('CAST(waktu_mulai AS TIME) ASC')
            ->get();

        $allText = '';
        $lastTanggal = null;
        $index = 0;

        $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
                 'Saturday' => 'Sabtu'];

        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        foreach ($agendas as $agenda) {
            $currentTanggal = $agenda->tanggal;

            // Tambah separator SEBELUM agenda (kecuali agenda pertama)
            if ($index > 0) {
                $allText .= "\n===========================\n\n";
            }

            // Header HANYA jika tanggal BERBEDA
            if ($lastTanggal != $currentTanggal) {
                $tanggalFormatted = $hari[date('l', strtotime($agenda->tanggal))] . ', ' .
                                    date('j', strtotime($agenda->tanggal)) . ' ' .
                                    $bulan[date('n', strtotime($agenda->tanggal))] . ' ' .
                                    date('Y', strtotime($agenda->tanggal));

                $allText .= "*AGENDA LURAH & PAMONG KALURAHAN TRIRENGGO*\n";
                $allText .= $tanggalFormatted . "\n\n";

                $lastTanggal = $currentTanggal;
            }

            // Surat dari
            if ($agenda->penyelenggara) {
                $allText .= "Surat dari : *" . $agenda->penyelenggara . "*\n";
            } else {
                $allText .= "Surat dari : *-*\n";
            }

            // Agenda
            $allText .= "Agenda : *" . $agenda->judul . "*\n";
            $allText .= "di : " . $agenda->tempat . "\n";

            // Format waktu
            $waktuMulai = $agenda->waktu_mulai;
            if (strpos($waktuMulai, ' ') !== false) {
                $parts = explode(' ', $waktuMulai);
                $waktuMulai = end($parts);
            }
            if (strlen($waktuMulai) > 8) {
                $waktuMulai = substr($waktuMulai, -8);
            }
            $waktuMulaiDisplay = date('H.i', strtotime($waktuMulai));

            $waktuSelesai = $agenda->waktu_selesai;
            if ($waktuSelesai) {
                if (strpos($waktuSelesai, ' ') !== false) {
                    $parts = explode(' ', $waktuSelesai);
                    $waktuSelesai = end($parts);
                }
                if (strlen($waktuSelesai) > 8) {
                    $waktuSelesai = substr($waktuSelesai, -8);
                }
                $waktuSelesaiDisplay = date('H.i', strtotime($waktuSelesai));
            } else {
                $waktuSelesaiDisplay = null;
            }

            // Pukul
            if ($waktuSelesaiDisplay) {
                $allText .= "Pukul : *" . $waktuMulaiDisplay . " WIB - " . $waktuSelesaiDisplay . " WIB*\n";
            } else {
                $allText .= "Pukul : *" . $waktuMulaiDisplay . " WIB*\n";
            }

            // ✅ HADIR: Urutkan Lurah di paling atas, lalu pegawai lain
            $allText .= "\nHadir : \n";

            $pegawaiList = $agenda->pegawai;

            // Pisahkan Lurah dan pegawai biasa
            $lurah = null;
            $pegawaiBiasa = [];

            foreach ($pegawaiList as $pegawai) {
                // Cek apakah pegawai adalah Lurah (bisa berdasarkan nama atau unit_kerja)
                if (stripos($pegawai->nama_pegawai, 'lurah') !== false || stripos($pegawai->unit_kerja, 'lurah') !== false) {
                    $lurah = $pegawai;
                } else {
                    $pegawaiBiasa[] = $pegawai;
                }
            }

            // Urutkan pegawai biasa berdasarkan nama
            $pegawaiBiasa = collect($pegawaiBiasa)->sortBy('nama_pegawai');

            $urutan = 1;

            // Tampilkan Lurah terlebih dahulu
            if ($lurah) {
                $allText .= $urutan . ". " . $lurah->nama_pegawai;
                if ($lurah->unit_kerja) {
                    $allText .= " (" . $lurah->unit_kerja . ")";
                }
                $allText .= "\n";
                $urutan++;
            }

            // Tampilkan pegawai biasa
            foreach ($pegawaiBiasa as $pegawai) {
                $allText .= $urutan . ". " . $pegawai->nama_pegawai;
                if ($pegawai->unit_kerja) {
                    $allText .= " (" . $pegawai->unit_kerja . ")";
                }
                $allText .= "\n";
                $urutan++;
            }

            $index++;
        }

        $allText = rtrim($allText);

        return response()->json([
            'success' => true,
            'text' => $allText
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Format waktu helper
     */
    private function formatWaktu($waktu)
    {
        if (strpos($waktu, ' ') !== false) {
            $parts = explode(' ', $waktu);
            $waktu = end($parts);
        }
        if (strlen($waktu) > 8) {
            $waktu = substr($waktu, -8);
        }
        return $waktu;
    }

    /**
     * UPDATE KEHADIRAN
     */
    public function updateKehadiran(Request $request, $id)
    {
        $request->validate([
            'kehadiran' => 'required|in:hadir,tidak_hadir'
        ]);

        $pegawai = AgendaPegawai::findOrFail($id);
        $pegawai->update(['kehadiran' => $request->kehadiran]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil diperbarui'
        ]);
    }

    /**
     * RESET KEHADIRAN
     */
    public function resetKehadiran($id)
    {
        $pegawai = AgendaPegawai::findOrFail($id);
        $pegawai->update(['kehadiran' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil direset'
        ]);
    }
}
