@extends('layouts.pegawai')

@section('title', 'Jadwal Saya')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-calendar-check mr-2 text-emerald-600 dark:text-emerald-400"></i>
            Jadwal Saya
        </h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Lihat dan konfirmasi kehadiran agenda</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Filter Section - Sederhana -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-filter mr-2 text-emerald-500"></i>
                Filter Jadwal
            </h3>
        </div>
        <div class="p-5">
            <form action="{{ route('pegawai.agendas.index') }}" method="GET">
                <div class="flex flex-wrap items-end gap-3">
                    <!-- Tanggal -->
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-gray-500"></i>
                            Tanggal
                        </label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Status -->
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-tag mr-1 text-gray-500"></i>
                            Status
                        </label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="direncanakan" {{ request('status') == 'direncanakan' ? 'selected' : '' }}>📅 Direncanakan</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>🟢 Berlangsung</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                        </select>
                    </div>

                    <!-- Pencarian -->
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-search mr-1 text-gray-500"></i>
                            Cari Agenda
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Judul / Tempat..."
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('pegawai.agendas.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($agendas as $agenda)
        @php
            $kehadiran = $agenda->pegawai->where('nama_pegawai', Auth::user()->name)->first();
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition overflow-hidden">
            <!-- Status Bar -->
            <div class="h-1
                @if($agenda->status == 'direncanakan') bg-yellow-500
                @elseif($agenda->status == 'berlangsung') bg-green-500
                @else bg-blue-500 @endif">
            </div>

            <div class="p-5">
                <!-- Status Badge & Date -->
                <div class="flex justify-between items-start mb-3">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($agenda->status == 'direncanakan') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                        @elseif($agenda->status == 'berlangsung') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 @endif">
                        @if($agenda->status == 'direncanakan') 📅 Direncanakan
                        @elseif($agenda->status == 'berlangsung') 🟢 Berlangsung
                        @else ✅ Selesai @endif
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d M Y') }}
                    </span>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-2">{{ $agenda->judul }}</h3>

                <!-- Info -->
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock w-4 text-gray-400"></i>
                        <span>{{ date('H:i', strtotime($agenda->waktu_mulai)) }} WIB</span>
                        @if($agenda->waktu_selesai)
                            <span>- {{ date('H:i', strtotime($agenda->waktu_selesai)) }} WIB</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt w-4 text-gray-400"></i>
                        <span class="truncate">{{ $agenda->tempat }}</span>
                    </div>
                </div>

                <!-- Kehadiran Status -->
                <div class="mb-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Status Kehadiran:</span>
                        @if($kehadiran && $kehadiran->kehadiran == 'hadir')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <i class="fas fa-check-circle mr-1"></i> Hadir
                            </span>
                        @elseif($kehadiran && $kehadiran->kehadiran == 'tidak_hadir')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                <i class="fas fa-times-circle mr-1"></i> Tidak Hadir
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                <i class="fas fa-clock mr-1"></i> Belum Konfirmasi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('pegawai.agendas.show', $agenda->id) }}"
                   class="block w-full text-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white rounded-lg transition">
                    <i class="fas fa-eye mr-1"></i> Lihat Detail & Konfirmasi
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-calendar-times text-5xl text-gray-400 dark:text-gray-600 mb-3 block"></i>
            <p class="text-gray-500 dark:text-gray-400">Tidak ada agenda untuk Anda</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $agendas->withQueryString()->links() }}
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
