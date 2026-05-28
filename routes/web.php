<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Pegawai\AgendaController as PegawaiAgendaController;

// ==================== GUEST ROUTES ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ==================== PROTECTED ROUTES ====================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Redirect root berdasarkan role
    Route::get('/', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.agendas.index');
        }
        return redirect()->route('pegawai.agendas.index');
    });

    // ==================== PROFILE ROUTES (Semua User) ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/password', [ProfileController::class, 'showChangePasswordForm'])->name('password');
        Route::put('/password', [ProfileController::class, 'changePassword'])->name('password.update');
        Route::get('/email', [ProfileController::class, 'showChangeEmailForm'])->name('email');
        Route::put('/email', [ProfileController::class, 'changeEmail'])->name('email.update');
        Route::get('/email/verify/{token}', [ProfileController::class, 'verifyNewEmail'])->name('verify-email');
        Route::post('/email/resend', [ProfileController::class, 'resendVerification'])->name('email.resend');
    });

    // ==================== ADMIN ROUTES ====================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard redirect
        Route::get('/', fn() => redirect()->route('agendas.index'));

        // ✅ AGENDA ADMIN - Tulis manual semua route
        Route::get('/agendas', [AdminAgendaController::class, 'index'])->name('agendas.index');
        Route::get('/agendas/create', [AdminAgendaController::class, 'create'])->name('agendas.create');
        Route::post('/agendas', [AdminAgendaController::class, 'store'])->name('agendas.store');
        Route::get('/agendas/{agenda}', [AdminAgendaController::class, 'show'])->name('agendas.show');
        Route::get('/agendas/{agenda}/edit', [AdminAgendaController::class, 'edit'])->name('agendas.edit');
        Route::put('/agendas/{agenda}', [AdminAgendaController::class, 'update'])->name('agendas.update');
        Route::delete('/agendas/{agenda}', [AdminAgendaController::class, 'destroy'])->name('agendas.destroy');

        // Share & Kehadiran
        Route::post('/agendas/{agenda}/share', [AdminAgendaController::class, 'share'])->name('agendas.share');
        Route::patch('/agendas-pegawai/{pegawai}/kehadiran', [AdminAgendaController::class, 'updateKehadiran'])->name('agendas.update-kehadiran');
        Route::delete('/agendas-pegawai/{pegawai}/reset-kehadiran', [AdminAgendaController::class, 'resetKehadiran'])->name('agendas.reset-kehadiran');

        // ✅ KELOLA PEGAWAI
        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('pegawai.show');
        Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
        Route::post('/pegawai/{pegawai}/toggle-status', [PegawaiController::class, 'toggleStatus'])->name('pegawai.toggle-status');
    });

    // ==================== PEGAWAI ROUTES ====================
    Route::prefix('pegawai')->name('pegawai.')->group(function () {

        // Dashboard redirect
        Route::get('/', fn() => redirect()->route('pegawai.agendas.index'));

        // ✅ AGENDA PEGAWAI
        Route::get('/agendas', [PegawaiAgendaController::class, 'index'])->name('agendas.index');
        Route::get('/agendas/{agenda}', [PegawaiAgendaController::class, 'show'])->name('agendas.show');
        Route::patch('/agendas/{agenda}/kehadiran', [PegawaiAgendaController::class, 'updateKehadiran'])->name('agendas.update-kehadiran');
    });
});
