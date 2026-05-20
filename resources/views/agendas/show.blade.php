@extends('layouts.app')

@section('title', 'Detail Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-calendar-alt text-2xl text-blue-600 mr-3"></i>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Agenda</h1>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('agendas.edit', $agenda->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <button type="button" class="btn-share inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg" data-id="{{ $agenda->id }}">
                <i class="fas fa-share-alt mr-2"></i>
                Share
            </button>
            <a href="{{ route('agendas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Detail Agenda -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-info-circle mr-2"></i>
                Informasi Agenda
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul -->
                <div class="md:col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-heading text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Judul Agenda</p>
                            <p class="text-lg font-semibold">{{ $agenda->judul }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($agenda->deskripsi)
                <div class="md:col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-align-left text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Deskripsi</p>
                            <p class="text-gray-900 dark:text-white">{{ $agenda->deskripsi }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tanggal -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Waktu -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Waktu</p>
                            <p class="font-medium">{{ date('H:i', strtotime($agenda->waktu_mulai)) }} WIB
                            @if($agenda->waktu_selesai)
                                - {{ date('H:i', strtotime($agenda->waktu_selesai)) }} WIB
                            @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Jam Surat -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-envelope text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Jam Surat Masuk</p>
                            <p class="font-medium">{{ $agenda->jam_surat ? date('H:i', strtotime($agenda->jam_surat)) : '-' }} WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Tempat -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-red-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tempat</p>
                            <p class="font-medium">{{ $agenda->tempat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Penyelenggara -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-teal-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-building text-teal-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Penyelenggara / Surat dari</p>
                            <p class="font-medium">{{ $agenda->penyelenggara ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-pink-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-tag text-pink-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Kategori</p>
                            <p class="font-medium">{{ ucfirst($agenda->kategori ?? '-') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        @php
                            $statusColor = match($agenda->status) {
                                'direncanakan' => 'bg-yellow-100 text-yellow-600',
                                'berlangsung' => 'bg-green-100 text-green-600',
                                'selesai' => 'bg-blue-100 text-blue-600',
                                default => 'bg-gray-100 text-gray-600'
                            };
                        @endphp
                        <div class="{{ $statusColor }} p-3 rounded-lg mr-4">
                            <i class="fas fa-flag text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <p class="font-medium">{{ ucfirst($agenda->status) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dibuat Pada -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-gray-200 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-gray-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Dibuat Pada</p>
                            <p class="font-medium">{{ $agenda->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($agenda->catatan)
                <div class="md:col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="bg-orange-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-sticky-note text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Catatan</p>
                            <p class="text-gray-900 dark:text-white">{{ $agenda->catatan }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">
                    <i class="fas fa-users mr-2"></i>
                    Daftar Peserta
                </h2>
                <span class="bg-white text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $agenda->pegawai->count() }} Orang
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Kerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($agenda->pegawai as $index => $p)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                                {{ $p->nama_pegawai }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $p->unit_kerja }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->kehadiran == 'hadir')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i> Hadir
                                </span>
                            @elseif($p->kehadiran == 'tidak_hadir')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak Hadir
                                </span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                    <i class="fas fa-clock mr-1"></i> Belum Diisi
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Share -->
<div id="shareModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeShareModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">
                    <i class="fas fa-share-alt text-blue-500 mr-2"></i>
                    Share Undangan
                </h3>
                <button onclick="closeShareModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <textarea id="shareText" rows="15" class="w-full p-3 border rounded-lg font-mono text-sm bg-gray-50 dark:bg-gray-900" readonly></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button onclick="copyShareText()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-copy mr-2"></i>Copy
                </button>
                <button onclick="shareViaWhatsApp()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                </button>
                <button onclick="closeShareModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-share').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        fetch('/agendas/' + id + '/share')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('shareText').value = data.text;
                    document.getElementById('shareModal').classList.remove('hidden');
                }
            });
    });
});

function copyShareText() {
    let textarea = document.getElementById('shareText');
    textarea.select();
    document.execCommand('copy');
    alert('Teks berhasil disalin!');
}

function shareViaWhatsApp() {
    let text = encodeURIComponent(document.getElementById('shareText').value);
    window.open('https://wa.me/?text=' + text, '_blank');
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
}
</script>
@endsection
