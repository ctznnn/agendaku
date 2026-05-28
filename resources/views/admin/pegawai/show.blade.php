@extends('layouts.admin')

@section('title', 'Detail Pegawai')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
            <h1 class="text-xl font-bold text-white">Detail Pegawai</h1>
            <p class="text-emerald-100 text-sm">Informasi lengkap pegawai</p>
        </div>

        <div class="p-6">
            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($pegawai->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $pegawai->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400">{{ $pegawai->email }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold mt-1
                        {{ $pegawai->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $pegawai->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $pegawai->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Unit Kerja</label>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $pegawai->unit_kerja ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Role</label>
                        <p class="font-medium">
                            @if($pegawai->isAdmin())
                                <span class="text-purple-600 dark:text-purple-400">Administrator</span>
                            @else
                                <span class="text-blue-600 dark:text-blue-400">Pegawai</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Terakhir Login</label>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $pegawai->last_login_at ? $pegawai->last_login_at->format('d/m/Y H:i') : 'Belum pernah login' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Bergabung Sejak</label>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $pegawai->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 mt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.pegawai.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('admin.pegawai.edit', $pegawai) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
