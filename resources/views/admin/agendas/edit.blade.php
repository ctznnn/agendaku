@extends('layouts.app')

@section('title', 'Edit Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-edit mr-2 text-yellow-600"></i>
            Edit Agenda
        </h1>
        <a href="{{ route('admin.agendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('admin.agendas.update', $agenda->id) }}" method="POST" id="agendaForm">
            @csrf
            @method('PUT')

            <div class="p-6">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <strong class="font-bold">Error! </strong>
                            <span class="ml-1">Ada masalah dengan input Anda.</span>
                        </div>
                        <ul class="mt-2 list-disc list-inside ml-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Informasi Agenda -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-info-circle mr-2 text-emerald-600"></i>
                        Informasi Agenda
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul -->
                        <div class="col-span-2">
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Agenda <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="judul"
                                   id="judul"
                                   value="{{ old('judul', $agenda->judul) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('judul') border-red-500 @enderror"
                                   placeholder="Masukkan judul agenda"
                                   required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                      placeholder="Masukkan deskripsi agenda">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   value="{{ old('tanggal', $agenda->tanggal) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('tanggal') border-red-500 @enderror"
                                   required>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu Mulai -->
                        <div>
                            <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                   name="waktu_mulai"
                                   id="waktu_mulai"
                                   value="{{ old('waktu_mulai', date('H:i', strtotime($agenda->waktu_mulai))) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('waktu_mulai') border-red-500 @enderror"
                                   required>
                            @error('waktu_mulai')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu Selesai -->
                        <div>
                            <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Selesai
                            </label>
                            <input type="time"
                                   name="waktu_selesai"
                                   id="waktu_selesai"
                                   value="{{ old('waktu_selesai', $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Tempat -->
                        <div>
                            <label for="tempat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tempat <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="tempat"
                                   id="tempat"
                                   value="{{ old('tempat', $agenda->tempat) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('tempat') border-red-500 @enderror"
                                   placeholder="Masukkan tempat"
                                   required>
                            @error('tempat')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Penyelenggara -->
                        <div>
                            <label for="penyelenggara" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penyelenggara
                            </label>
                            <input type="text"
                                   name="penyelenggara"
                                   id="penyelenggara"
                                   value="{{ old('penyelenggara', $agenda->penyelenggara) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                   placeholder="Masukkan penyelenggara">
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori
                            </label>
                            <select name="kategori"
                                    id="kategori"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Pilih Kategori</option>
                                <option value="rapat" {{ old('kategori', $agenda->kategori) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                <option value="sosialisasi" {{ old('kategori', $agenda->kategori) == 'sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                                <option value="pelatihan" {{ old('kategori', $agenda->kategori) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                <option value="workshop" {{ old('kategori', $agenda->kategori) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ old('kategori', $agenda->kategori) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="lainnya" {{ old('kategori', $agenda->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select name="status"
                                    id="status"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="direncanakan" {{ old('status', $agenda->status) == 'direncanakan' ? 'selected' : '' }}>📅 Direncanakan</option>
                                <option value="berlangsung" {{ old('status', $agenda->status) == 'berlangsung' ? 'selected' : '' }}>🟢 Berlangsung</option>
                                <option value="selesai" {{ old('status', $agenda->status) == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                <option value="batal" {{ old('status', $agenda->status) == 'batal' ? 'selected' : '' }}>❌ Batal</option>
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div class="col-span-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan
                            </label>
                            <textarea name="catatan"
                                      id="catatan"
                                      rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                      placeholder="Masukkan catatan tambahan">{{ old('catatan', $agenda->catatan) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Daftar Pegawai (Checkbox dari Database - Kelola Pegawai) -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-users mr-2 text-emerald-600"></i>
                            Daftar Pegawai <span class="text-red-500">*</span>
                        </h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Pilih minimal 1 pegawai</span>
                    </div>

                    @if(isset($pegawaiList) && $pegawaiList->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto p-2 border rounded-lg bg-gray-50 dark:bg-gray-700/30">
                            @foreach($pegawaiList as $pegawai)
                                <label class="flex items-start p-3 bg-white dark:bg-gray-800 rounded-lg border cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition">
                                    <input type="checkbox"
                                           name="pegawai_ids[]"
                                           value="{{ $pegawai->id }}"
                                           class="mt-1 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                           {{ in_array($pegawai->id, old('pegawai_ids', $selectedPegawai ?? [])) ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $pegawai->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-building mr-1"></i> {{ $pegawai->unit_kerja ?? 'Unit Kerja' }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $pegawai->email }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i> Centang checkbox untuk memilih pegawai yang hadir
                        </p>
                        @error('pegawai_ids')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            <i class="fas fa-users text-4xl text-gray-400 mb-2 block"></i>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data pegawai</p>
                            <a href="{{ route('admin.pegawai.create') }}" class="inline-block mt-2 text-emerald-600 hover:text-emerald-700 text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah pegawai dulu
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.agendas.index') }}"
                       class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition">
                        <i class="fas fa-save mr-1"></i> Update Agenda
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Validasi form sebelum submit
document.getElementById('agendaForm').addEventListener('submit', function(e) {
    let checkboxes = document.querySelectorAll('input[name="pegawai_ids[]"]:checked');

    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 pegawai!');
        return false;
    }

    // Validasi judul
    let judul = document.getElementById('judul').value.trim();
    if (judul === '') {
        e.preventDefault();
        alert('Judul agenda wajib diisi!');
        document.getElementById('judul').focus();
        return false;
    }

    // Validasi tanggal
    let tanggal = document.getElementById('tanggal').value;
    if (tanggal === '') {
        e.preventDefault();
        alert('Tanggal wajib diisi!');
        document.getElementById('tanggal').focus();
        return false;
    }

    // Validasi waktu mulai
    let waktuMulai = document.getElementById('waktu_mulai').value;
    if (waktuMulai === '') {
        e.preventDefault();
        alert('Waktu mulai wajib diisi!');
        document.getElementById('waktu_mulai').focus();
        return false;
    }

    // Validasi tempat
    let tempat = document.getElementById('tempat').value.trim();
    if (tempat === '') {
        e.preventDefault();
        alert('Tempat wajib diisi!');
        document.getElementById('tempat').focus();
        return false;
    }
});
</script>
@endsection
