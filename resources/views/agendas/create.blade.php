@extends('layouts.app')

@section('title', 'Tambah Agenda Baru')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Agenda Baru</h1>
        <a href="{{ route('agendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('agendas.store') }}" method="POST" id="agendaForm">
            @csrf

            <div class="p-6">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <strong class="font-bold">Error!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Informasi Agenda -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
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
                                   value="{{ old('judul') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('judul') border-red-500 @enderror"
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
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                      placeholder="Masukkan deskripsi agenda">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   value="{{ old('tanggal') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('tanggal') border-red-500 @enderror"
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
                                   value="{{ old('waktu_mulai') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('waktu_mulai') border-red-500 @enderror"
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
                                   value="{{ old('waktu_selesai') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- Tempat -->
                        <div>
                            <label for="tempat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tempat <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="tempat"
                                   id="tempat"
                                   value="{{ old('tempat') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('tempat') border-red-500 @enderror"
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
                                   value="{{ old('penyelenggara') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                   placeholder="Masukkan penyelenggara">
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori
                            </label>
                            <select name="kategori"
                                    id="kategori"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Pilih Kategori</option>
                                <option value="rapat" {{ old('kategori') == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                <option value="sosialisasi" {{ old('kategori') == 'sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                                <option value="pelatihan" {{ old('kategori') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                <option value="workshop" {{ old('kategori') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ old('kategori') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select name="status"
                                    id="status"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="direncanakan" {{ old('status', 'direncanakan') == 'direncanakan' ? 'selected' : '' }}>Direncanakan</option>
                                <option value="berlangsung" {{ old('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
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
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                      placeholder="Masukkan catatan tambahan">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Daftar Pegawai -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Daftar Pegawai / Peserta <span class="text-red-500">*</span>
                        </h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Minimal 1 peserta</span>
                    </div>

                    <div id="pegawai-container" class="space-y-4">
                        @if(old('pegawai'))
                            @foreach(old('pegawai') as $index => $pegawai)
                                <div class="pegawai-item bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700" data-index="{{ $index }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Nama Pegawai <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   name="pegawai[{{ $index }}][nama]"
                                                   value="{{ $pegawai['nama'] }}"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="Nama lengkap"
                                                   required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                NIP
                                            </label>
                                            <input type="text"
                                                   name="pegawai[{{ $index }}][nip]"
                                                   value="{{ $pegawai['nip'] }}"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="NIP">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Unit Kerja <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   name="pegawai[{{ $index }}][unit_kerja]"
                                                   value="{{ $pegawai['unit_kerja'] }}"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="Unit kerja"
                                                   required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Jabatan
                                            </label>
                                            <div class="flex space-x-2">
                                                <input type="text"
                                                       name="pegawai[{{ $index }}][jabatan]"
                                                       value="{{ $pegawai['jabatan'] }}"
                                                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                       placeholder="Jabatan">
                                                @if($index > 0)
                                                    <button type="button"
                                                            class="remove-pegawai px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"
                                                            title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default 3 pegawai -->
                            @for($i = 0; $i < 3; $i++)
                                <div class="pegawai-item bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700" data-index="{{ $i }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Nama Pegawai <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   name="pegawai[{{ $i }}][nama]"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="Nama lengkap"
                                                   value="{{ old('pegawai.'.$i.'.nama') }}"
                                                   required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                NIP
                                            </label>
                                            <input type="text"
                                                   name="pegawai[{{ $i }}][nip]"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="NIP"
                                                   value="{{ old('pegawai.'.$i.'.nip') }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Unit Kerja <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                   name="pegawai[{{ $i }}][unit_kerja]"
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="Unit kerja"
                                                   value="{{ old('pegawai.'.$i.'.unit_kerja') }}"
                                                   required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Jabatan
                                            </label>
                                            <div class="flex space-x-2">
                                                <input type="text"
                                                       name="pegawai[{{ $i }}][jabatan]"
                                                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                       placeholder="Jabatan"
                                                       value="{{ old('pegawai.'.$i.'.jabatan') }}">
                                                @if($i > 0)
                                                    <button type="button"
                                                            class="remove-pegawai px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"
                                                            title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>

                    <!-- Tombol Tambah Pegawai -->
                    <button type="button"
                            id="add-pegawai"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Pegawai
                    </button>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('agendas.index') }}"
                       class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Simpan Agenda
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pegawaiIndex = {{ old('pegawai') ? count(old('pegawai')) : 3 }};

// Fungsi untuk menambah form pegawai
document.getElementById('add-pegawai').addEventListener('click', function() {
    let container = document.getElementById('pegawai-container');

    let template = `
        <div class="pegawai-item bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700" data-index="${pegawaiIndex}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Pegawai <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="pegawai[${pegawaiIndex}][nama]"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           placeholder="Nama lengkap"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        NIP
                    </label>
                    <input type="text"
                           name="pegawai[${pegawaiIndex}][nip]"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           placeholder="NIP">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Unit Kerja <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="pegawai[${pegawaiIndex}][unit_kerja]"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                           placeholder="Unit kerja"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jabatan
                    </label>
                    <div class="flex space-x-2">
                        <input type="text"
                               name="pegawai[${pegawaiIndex}][jabatan]"
                               class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Jabatan">
                        <button type="button"
                                class="remove-pegawai px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"
                                title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', template);
    pegawaiIndex++;
});

// Fungsi untuk menghapus form pegawai
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-pegawai')) {
        if (confirm('Yakin ingin menghapus pegawai ini?')) {
            let pegawaiItem = e.target.closest('.pegawai-item');
            pegawaiItem.remove();
        }
    }
});

// Validasi form sebelum submit
document.getElementById('agendaForm').addEventListener('submit', function(e) {
    let pegawaiItems = document.querySelectorAll('.pegawai-item');

    if (pegawaiItems.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 pegawai/peserta');
        return;
    }

    // Validasi setiap input required di pegawai
    let isValid = true;
    pegawaiItems.forEach(function(item) {
        let namaInput = item.querySelector('input[name*="[nama]"]');
        let unitInput = item.querySelector('input[name*="[unit_kerja]"]');

        if (!namaInput.value.trim()) {
            isValid = false;
            namaInput.classList.add('border-red-500');
        } else {
            namaInput.classList.remove('border-red-500');
        }

        if (!unitInput.value.trim()) {
            isValid = false;
            unitInput.classList.add('border-red-500');
        } else {
            unitInput.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi data pegawai yang wajib diisi (Nama dan Unit Kerja)');
    }
});
</script>
@endpush
