@extends('layouts.app')

@section('title', 'Edit Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-edit text-2xl text-yellow-600 mr-3"></i>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Agenda</h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('agendas.show', $agenda->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                <i class="fas fa-eye mr-2"></i>
                Lihat Detail
            </a>
            <a href="{{ route('agendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Form Edit -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('agendas.update', $agenda->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Judul -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-heading mr-1 text-gray-500"></i>
                            Judul Agenda <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul', $agenda->judul) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-1 text-gray-500"></i>
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-gray-500"></i>
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $agenda->tanggal->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               required>
                    </div>

                    <!-- Waktu -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock mr-1 text-gray-500"></i>
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', date('H:i', strtotime($agenda->waktu_mulai))) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock mr-1 text-gray-500"></i>
                                Waktu Selesai
                            </label>
                            <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Jam Surat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-1 text-gray-500"></i>
                            Jam Surat Masuk
                        </label>
                        <input type="time" name="jam_surat" value="{{ old('jam_surat', $agenda->jam_surat ? date('H:i', strtotime($agenda->jam_surat)) : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada surat</p>
                    </div>

                    <!-- Tempat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-gray-500"></i>
                            Tempat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat" value="{{ old('tempat', $agenda->tempat) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               required>
                    </div>

                    <!-- Penyelenggara -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-building mr-1 text-gray-500"></i>
                            Penyelenggara / Surat dari
                        </label>
                        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $agenda->penyelenggara) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Asal surat / penyelenggara">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag mr-1 text-gray-500"></i>
                            Kategori
                        </label>
                        <select name="kategori" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Pilih Kategori</option>
                            <option value="rapat" {{ old('kategori', $agenda->kategori) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                            <option value="sosialisasi" {{ old('kategori', $agenda->kategori) == 'sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                            <option value="pelatihan" {{ old('kategori', $agenda->kategori) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            <option value="workshop" {{ old('kategori', $agenda->kategori) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="seminar" {{ old('kategori', $agenda->kategori) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-flag mr-1 text-gray-500"></i>
                            Status
                        </label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="direncanakan" {{ old('status', $agenda->status) == 'direncanakan' ? 'selected' : '' }}>Direncanakan</option>
                            <option value="berlangsung" {{ old('status', $agenda->status) == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ old('status', $agenda->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $agenda->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-sticky-note mr-1 text-gray-500"></i>
                            Catatan
                        </label>
                        <textarea name="catatan" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('catatan', $agenda->catatan) }}</textarea>
                    </div>
                </div>

                <!-- Daftar Peserta -->
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-users text-blue-600 mr-2"></i>
                            Daftar Peserta
                        </h3>
                        <button type="button" id="addPeserta" class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>

                    <div id="pesertaContainer">
                        @foreach($agenda->pegawai as $index => $p)
                        <div class="peserta-item grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="pegawai[{{ $index }}][nama]" value="{{ $p->nama_pegawai }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Unit Kerja <span class="text-red-500">*</span></label>
                                <input type="text" name="pegawai[{{ $index }}][unit_kerja]" value="{{ $p->unit_kerja }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kehadiran</label>
                                <div class="flex gap-2">
                                    <select name="pegawai[{{ $index }}][kehadiran]" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="">Belum Diisi</option>
                                        <option value="hadir" {{ $p->kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="tidak_hadir" {{ $p->kehadiran == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    </select>
                                    @if($index > 0)
                                    <button type="button" class="removePeserta px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('agendas.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        <i class="fas fa-save mr-2"></i> Update Agenda
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let pesertaCount = {{ $agenda->pegawai->count() }};

    // Tambah peserta
    document.getElementById('addPeserta').addEventListener('click', function() {
        let container = document.getElementById('pesertaContainer');
        let div = document.createElement('div');
        div.className = 'peserta-item grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg';
        div.innerHTML = `
            <div>
                <label class="block text-sm font-medium mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="pegawai[${pesertaCount}][nama]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Unit Kerja <span class="text-red-500">*</span></label>
                <input type="text" name="pegawai[${pesertaCount}][unit_kerja]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kehadiran</label>
                <div class="flex gap-2">
                    <select name="pegawai[${pesertaCount}][kehadiran]" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Belum Diisi</option>
                        <option value="hadir">Hadir</option>
                        <option value="tidak_hadir">Tidak Hadir</option>
                    </select>
                    <button type="button" class="removePeserta px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
        pesertaCount++;
    });

    // Hapus peserta
    document.addEventListener('click', function(e) {
        if (e.target.closest('.removePeserta')) {
            e.target.closest('.peserta-item').remove();
        }
    });
});
</script>
@endsection
