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
        // Ambil semua agenda
        $agendas = Agenda::all();
        foreach ($agendas as $agenda) {
            $this->updateAgendaStatus($agenda);
        }
    }

    /**
     * Update status single agenda
     * Logika:
     * - Tanggal < hari ini = SELESAI
     * - Tanggal = hari ini:
     *    - Waktu sekarang >= waktu mulai = BERLANGSUNG
     *    - Waktu sekarang < waktu mulai = DIRENCANAKAN
     * - Tanggal > hari ini = DIRENCANAKAN
     */
    private function updateAgendaStatus($agenda)
    {
        try {
            $now = Carbon::now();
            $tanggalAgenda = Carbon::parse($agenda->tanggal);

            // Bersihkan waktu mulai
            $waktuMulaiStr = $this->cleanTimeString($agenda->waktu_mulai);
            if (!$waktuMulaiStr) {
                return;
            }

            $waktuMulai = Carbon::parse($agenda->tanggal . ' ' . $waktuMulaiStr);

            // Jika status batal, skip
            if ($agenda->status == 'batal') {
                return;
            }

            $newStatus = $agenda->status;

            // Jika tanggal sudah lewat (H-1, H-2, dst) -> SELESAI
            if ($tanggalAgenda->lt($now->startOfDay())) {
                $newStatus = 'selesai';
            }
            // Jika tanggal hari ini (H+0)
            elseif ($tanggalAgenda->isToday()) {
                // Jika waktu sekarang sudah melewati atau sama dengan waktu mulai -> BERLANGSUNG
                if ($now->greaterThanOrEqualTo($waktuMulai)) {
                    $newStatus = 'berlangsung';
                } else {
                    $newStatus = 'direncanakan';
                }
            }
            // Jika tanggal masih future (H+1, H+2, dst) -> DIRENCANAKAN
            elseif ($tanggalAgenda->gt($now->startOfDay())) {
                $newStatus = 'direncanakan';
            }

            // Update jika berbeda
            if ($agenda->status != $newStatus) {
                $agenda->update(['status' => $newStatus]);
                \Log::info('Status agenda "' . $agenda->judul . '" berubah dari ' . $agenda->status . ' menjadi ' . $newStatus);
            }

        } catch (\Exception $e) {
            \Log::error('Error update status: ' . $e->getMessage());
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

        // Jika mengandung spasi, ambil bagian terakhir (jam)
        if (strpos($waktu, ' ') !== false) {
            $parts = explode(' ', $waktu);
            $waktu = end($parts);
        }

        // Jika masih panjang, ambil 8 karakter terakhir
        if (strlen($waktu) > 8) {
            $waktu = substr($waktu, -8);
        }

        // Jika format H:i, tambahkan :00
        if (preg_match('/^\d{2}:\d{2}$/', $waktu)) {
            $waktu = $waktu . ':00';
        }

        // Jika format sudah benar H:i:s
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $waktu)) {
            return $waktu;
        }

        return null;
    }

    /**
     * INDEX - Daftar agenda (diurutkan dari yang terbaru)
     */
        /**
 * INDEX - Daftar agenda
 */
public function index(Request $request)
{
    // Update status semua agenda setiap kali halaman diakses
    $this->updateAllStatuses();

    $query = Agenda::with('pegawai');

    // ✅ Filter berdasarkan TANGGAL (satu tanggal)
    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal', $request->tanggal);
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // ✅ Filter pencarian (bisa cari judul, tempat, penyelenggara)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', '%' . $search . '%')
                ->orWhere('tempat', 'like', '%' . $search . '%')
                ->orWhere('penyelenggara', 'like', '%' . $search . '%');
        });
    }

    // Urutkan dari yang terbaru dibuat
    $agendas = $query->orderBy('created_at', 'desc')
        ->orderBy('tanggal', 'desc')
        ->orderBy('waktu_mulai', 'asc')
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
            // Format waktu
            $waktuMulai = date('H:i:s', strtotime($request->waktu_mulai));
            $waktuSelesai = $request->waktu_selesai ? date('H:i:s', strtotime($request->waktu_selesai)) : null;

            // Hitung status awal
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

            // Simpan pegawai
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

        // Update status agenda
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

        // Update status agenda
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

            // Hitung status baru
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

            // Hapus pegawai lama
            $agenda->pegawai()->delete();

            // Simpan pegawai baru
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
     * SHARE - Share undangan
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

            $waktuMulai = date('H.i', strtotime($agenda->waktu_mulai));
            $waktuSelesai = $agenda->waktu_selesai ? date('H.i', strtotime($agenda->waktu_selesai)) : 'Selesai';

            $teks = "*AGENDA LURAH & PAMONG KALURAHAN TRIRENGGO*\n";

            // Surat dari di awal
            if ($agenda->penyelenggara) {
                $teks .= "Surat dari : *" . $agenda->penyelenggara . "*\n";
            }

            $teks .= $tanggalFormatted . "\n\n";

            if ($waktuSelesai == 'Selesai') {
                $teks .= "Pukul : *" . $waktuMulai . " WIB*\n";
            } else {
                $teks .= "Pukul : *" . $waktuMulai . " WIB - " . $waktuSelesai . " WIB*\n";
            }

            $teks .= "Agenda : *" . $agenda->judul . "*\n";
            $teks .= "di : " . $agenda->tempat . "\n\n";

            $teks .= "Hadir : \n";
            foreach ($agenda->pegawai as $pegawai) {
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
