<?php
// routes/web.php

use App\Http\Controllers\AgendaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('agendas.index');
});

Route::resource('agendas', AgendaController::class);
Route::get('agendas/{agenda}/share', [AgendaController::class, 'share'])->name('agendas.share');
Route::post('agendas/pegawai/{pegawai}/kehadiran', [AgendaController::class, 'updateKehadiran'])->name('agendas.kehadiran');
