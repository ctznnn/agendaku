<?php
// app/Http/Controllers/Pegawai/AgendaController.php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AgendaPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    // HAPUS constructor ini:
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // Langsung tulis method tanpa constructor

    // Halaman agenda untuk pegawai
    public function index(Request $request)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = Agenda::with('pegawai');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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

        return view('pegawai.agendas.index', compact('agendas'));
    }

    // Detail agenda untuk pegawai
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $agenda = Agenda::with('pegawai')->findOrFail($id);

        // Cari kehadiran user yang login
        $kehadiranSaya = $agenda->pegawai->where('nama_pegawai', Auth::user()->name)->first();

        return view('pegawai.agendas.show', compact('agenda', 'kehadiranSaya'));
    }

    // Update kehadiran untuk pegawai
    public function updateKehadiran(Request $request, $id)
    {
        $request->validate([
            'kehadiran' => 'required|in:hadir,tidak_hadir'
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $agenda = Agenda::findOrFail($id);

        // Cari data kehadiran pegawai
        $kehadiran = AgendaPegawai::where('agenda_id', $id)
            ->where('nama_pegawai', Auth::user()->name)
            ->first();

        if ($kehadiran) {
            $kehadiran->update([
                'kehadiran' => $request->kehadiran
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil diperbarui'
        ]);
    }
}
