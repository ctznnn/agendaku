@extends('layouts.admin')

@section('title', 'Edit Pegawai')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-user-edit mr-2 text-yellow-600 dark:text-yellow-400"></i>
                Edit Pegawai
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Ubah data pegawai yang terdaftar</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pegawai.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <a href="{{ route('admin.pegawai.show', $pegawai) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fas fa-eye mr-2"></i> Lihat Detail
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900/50 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong class="font-bold">Error!</strong>
                <span class="ml-1">Ada masalah dengan input Anda.</span>
            </div>
            <ul class="mt-2 list-disc list-inside ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-user-edit text-white text-xl"></i>
                <div>
                    <h2 class="text-lg font-semibold text-white">Form Edit Pegawai</h2>
                    <p class="text-yellow-100 text-sm">Ubah data pegawai yang diperlukan</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Informasi Dasar -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-info-circle mr-2 text-emerald-600 dark:text-emerald-400"></i>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email
                            </label>
                            <div class="relative">
                                <input type="email" value="{{ $pegawai->email }}" disabled
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-lock text-gray-400 dark:text-gray-500"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Email tidak dapat diubah.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Unit Kerja
                            </label>
                            <input type="text" name="unit_kerja" value="{{ old('unit_kerja', $pegawai->unit_kerja) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Contoh: Bendahara, Kesra, Umum">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status Akun
                            </label>
                            <select name="is_active"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="1" {{ $pegawai->is_active ? 'selected' : '' }}>🟢 Aktif</option>
                                <option value="0" {{ !$pegawai->is_active ? 'selected' : '' }}>🔴 Nonaktif</option>
                            </select>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pegawai nonaktif tidak dapat login ke sistem.</p>
                        </div>
                    </div>
                </div>

                <!-- Reset Password -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-key mr-2 text-yellow-600 dark:text-yellow-400"></i>
                        Reset Password (Opsional)
                    </h3>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                            <div class="text-sm text-yellow-700 dark:text-yellow-300">
                                <p class="font-medium">Perhatian!</p>
                                <p class="text-xs">Reset password hanya dilakukan jika pegawai lupa password. Isi form di bawah hanya jika ingin mengubah password.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password Baru
                            </label>
                            <input type="password" name="new_password"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Konfirmasi password baru">
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-chart-line mr-2 text-gray-500 dark:text-gray-400"></i>
                        Informasi Tambahan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Terakhir Login</label>
                            <p class="text-gray-800 dark:text-white font-medium">
                                {{ $pegawai->last_login_at ? $pegawai->last_login_at->format('d F Y H:i') : 'Belum pernah login' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                            <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase">Bergabung Sejak</label>
                            <p class="text-gray-800 dark:text-white font-medium">
                                {{ $pegawai->created_at->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.pegawai.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                        <i class="fas fa-save mr-1"></i> Update Pegawai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
