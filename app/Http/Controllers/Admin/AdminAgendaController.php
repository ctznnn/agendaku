<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\AgendaPegawai;   

class AdminAgendaController extends Controller
{
    /**
 * INDEX - Daftar agenda (Admin)
 */
public function index(Request $request)
{
    // ✅ Update status semua agenda sebelum ditampilkan
    Agenda::updateSemuaStatus();

    $query = Agenda::with('pegawai');

    // Filter tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter pencarian
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

    return view('admin.agendas.index', compact('agendas'));
}
}
