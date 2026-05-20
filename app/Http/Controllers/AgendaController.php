<?php
// app/Http/Controllers/AgendaController.php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    // INDEX - Menampilkan daftar agenda
    public function index(Request $request)
    {
        $query = Agenda::with('pegawai');

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('tempat', 'like', '%' . $request->search . '%')
                    ->orWhere('penyelenggara', 'like', '%' . $request->search . '%');
            });
        }

        $agendas = $query->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->paginate(10);

        return view('agendas.index', compact('agendas'));
    }

    // CREATE - Menampilkan form tambah agenda
    public function create()
    {
        return view('agendas.create');
    }

    // STORE - Menyimpan agenda baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'tempat' => 'required|string|max:255',
            'pegawai' => 'required|array|min:1',
            'pegawai.*.nama' => 'required|string|max:255',
            'pegawai.*.unit_kerja' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create agenda
            $agenda = Agenda::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'tempat' => $request->tempat,
                'penyelenggara' => $request->penyelenggara,
                'kategori' => $request->kategori,
                'status' => $request->status ?? 'direncanakan',
                'catatan' => $request->catatan
            ]);

            // Create multiple pegawai
            foreach ($request->pegawai as $pegawaiData) {
                AgendaPegawai::create([
                    'agenda_id' => $agenda->id,
                    'nama_pegawai' => $pegawaiData['nama'],
                    'unit_kerja' => $pegawaiData['unit_kerja'],
                    'kehadiran' => null
                ]);
            }

            DB::commit();
            return redirect()->route('agendas.index')
                ->with('success', 'Agenda "' . $agenda->judul . '" berhasil dibuat dengan ' . count($request->pegawai) . ' peserta.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat agenda: ' . $e->getMessage());
        }
    }

    // SHOW - Menampilkan detail agenda
    public function show(Agenda $agenda)
    {
        $agenda->load('pegawai');
        return view('agendas.show', compact('agenda'));
    }

    // EDIT - Menampilkan form edit agenda
    public function edit(Agenda $agenda)
    {
        $agenda->load('pegawai');
        return view('agendas.edit', compact('agenda'));
    }

    // UPDATE - Mengupdate agenda
    public function update(Request $request, Agenda $agenda)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'tempat' => 'required|string|max:255',
            'pegawai' => 'required|array|min:1',
            'pegawai.*.nama' => 'required|string|max:255',
            'pegawai.*.unit_kerja' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update agenda
            $agenda->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'tempat' => $request->tempat,
                'penyelenggara' => $request->penyelenggara,
                'kategori' => $request->kategori,
                'status' => $request->status,
                'catatan' => $request->catatan
            ]);

            // Delete existing pegawai
            $agenda->pegawai()->delete();

            // Create new pegawai
            foreach ($request->pegawai as $pegawaiData) {
                AgendaPegawai::create([
                    'agenda_id' => $agenda->id,
                    'nama_pegawai' => $pegawaiData['nama'],
                    'unit_kerja' => $pegawaiData['unit_kerja'],
                    'kehadiran' => $pegawaiData['kehadiran'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->route('agendas.index')
                ->with('success', 'Agenda "' . $agenda->judul . '" berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui agenda: ' . $e->getMessage());
        }
    }

    // DESTROY - Menghapus agenda
    public function destroy(Agenda $agenda)
    {
        try {
            $judul = $agenda->judul;
            $agenda->delete();
            return redirect()->route('agendas.index')
                ->with('success', 'Agenda "' . $judul . '" berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus agenda: ' . $e->getMessage());
        }
    }

    // SHARE - Membuat template undangan
    public function share(Agenda $agenda)
{
    $agenda->load('pegawai');

    // Format tanggal Indonesia
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

    // Build text
    $teks = "*AGENDA LURAH & PAMONG KALURAHAN TRIRENGGO*\n";
    $teks .= $tanggalFormatted . "\n\n";

    // Surat dari
    if ($agenda->penyelenggara) {
        $teks .= "Surat dari " . $agenda->penyelenggara . "\n";
    }

    // Waktu
    if ($waktuSelesai == 'Selesai') {
        $teks .= "Pkl. *" . $waktuMulai . " WIB*\n";
    } else {
        $teks .= "Pkl. *" . $waktuMulai . " WIB - " . $waktuSelesai . " WIB*\n";
    }

    // Acara
    $teks .= "agenda *" . $agenda->judul . "*\n";

    // Tempat
    $teks .= "di " . $agenda->tempat . "\n";

    // Daftar hadir
    $teks .= "Hadir: \n";
    foreach ($agenda->pegawai as $pegawai) {
        $teks .= "- " . $pegawai->nama_pegawai;
        if ($pegawai->unit_kerja) {
            $teks .= " (" . $pegawai->unit_kerja . ")";
        }
        $teks .= "\n";
    }

    // Hapus newline terakhir
    $teks = rtrim($teks);

    return response()->json([
        'success' => true,
        'text' => $teks
    ]);
}

    // UPDATE KEHADIRAN - Mengupdate status kehadiran pegawai
    public function updateKehadiran(Request $request, AgendaPegawai $pegawai)
    {
        $request->validate([
            'kehadiran' => 'required|in:hadir,tidak_hadir'
        ]);

        $pegawai->update(['kehadiran' => $request->kehadiran]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil diperbarui',
            'kehadiran_text' => $pegawai->kehadiran_text,
            'badge_color' => $pegawai->kehadiran_badge_color
        ]);
    }



    // RESET KEHADIRAN - Mereset status kehadiran pegawai
    public function resetKehadiran(AgendaPegawai $pegawai)
    {
        $pegawai->update(['kehadiran' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil direset'
        ]);
    }
}
