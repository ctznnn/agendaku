<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Constructor: semua method butuh login
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard untuk admin
     */
    public function adminDashboard()
    {
        // Statistik untuk admin
        $totalPegawai = User::where('role', 'pegawai')->count();
        $pegawaiAktif = User::where('role', 'pegawai')->where('is_active', true)->count();
        $pegawaiNonaktif = User::where('role', 'pegawai')->where('is_active', false)->count();
        $adminCount = User::where('role', 'admin')->count();

        // 5 pegawai terbaru
        $latestPegawai = User::where('role', 'pegawai')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalPegawai',
            'pegawaiAktif',
            'pegawaiNonaktif',
            'adminCount',
            'latestPegawai'
        ));
    }

    /**
     * Dashboard untuk pegawai
     */
    public function pegawaiDashboard()
    {
        $user = auth()->user();

        // Informasi pegawai
        $unitKerja = $user->unit_kerja ?? 'Belum diisi';
        $lastLogin = $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah login';

        return view('dashboard.pegawai', compact('user', 'unitKerja', 'lastLogin'));
    }
}
