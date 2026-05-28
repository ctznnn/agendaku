<?php
// app/Http/Controllers/Admin/PegawaiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    // HAPUS constructor dengan middleware!
    // Jangan pakai __construct() sama sekali

    // Atau jika ingin pakai middleware di Laravel 11+, gunakan这个方法:
    // public static function middleware(): array
    // {
    //     return ['auth', 'role:admin'];
    // }
    // TAPI LEBIH BAIK diatur di routes saja

    /**
     * Menampilkan daftar semua pegawai
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'pegawai');

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('unit_kerja', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $pegawais = $query->orderBy('name', 'asc')->paginate(10);

        return view('admin.pegawai.index', compact('pegawais'));
    }

    /**
     * Form tambah pegawai
     */
    public function create()
    {
        return view('admin.pegawai.create');
    }

    /**
     * Simpan pegawai baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $pegawai = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pegawai',
                'unit_kerja' => $request->unit_kerja,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai "' . $pegawai->name . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menambahkan pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Detail pegawai
     */
    public function show(User $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            abort(404);
        }
        return view('admin.pegawai.show', compact('pegawai'));
    }

    /**
     * Form edit pegawai
     */
    public function edit(User $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            abort(404);
        }
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    /**
     * Update pegawai
     */
    public function update(Request $request, User $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $pegawai->update([
            'name' => $request->name,
            'unit_kerja' => $request->unit_kerja,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Reset password jika diisi
        if ($request->filled('new_password')) {
            $request->validate(['new_password' => 'min:8|confirmed']);
            $pegawai->update(['password' => Hash::make($request->new_password)]);
        }

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Hapus pegawai
     */
    public function destroy(User $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            return response()->json(['success' => false, 'message' => 'Invalid user'], 404);
        }

        $name = $pegawai->name;
        $pegawai->delete();

        return response()->json(['success' => true, 'message' => 'Pegawai "' . $name . '" berhasil dihapus.']);
    }

    /**
     * Toggle status aktif/nonaktif via AJAX
     */
    public function toggleStatus(User $pegawai)
    {
        if ($pegawai->role !== 'pegawai') {
            return response()->json(['success' => false, 'message' => 'Invalid user'], 404);
        }

        $pegawai->update([
            'is_active' => !$pegawai->is_active
        ]);

        $status = $pegawai->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Pegawai berhasil {$status}",
            'is_active' => $pegawai->is_active
        ]);
    }
}
