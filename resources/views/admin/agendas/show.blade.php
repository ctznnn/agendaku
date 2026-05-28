@extends('layouts.app')

@section('title', 'Detail Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-file-alt mr-2 text-blue-600 dark:text-blue-400"></i>
            Detail Agenda
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.agendas.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
            <a href="{{ route('admin.agendas.edit', $agenda->id) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white rounded-lg transition">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <button type="button"
                    class="btn-share inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg transition"
                    data-id="{{ $agenda->id }}">
                <i class="fas fa-share-alt mr-2"></i>
                Share
            </button>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Status Banner -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700
            @if($agenda->status == 'direncanakan') bg-yellow-50 dark:bg-yellow-900/20
            @elseif($agenda->status == 'berlangsung') bg-green-50 dark:bg-green-900/20
            @elseif($agenda->status == 'selesai') bg-blue-50 dark:bg-blue-900/20
            @else bg-gray-50 dark:bg-gray-700/30 @endif">
            <div class="flex items-center gap-3">
                <i class="fas fa-info-circle text-xl
                    @if($agenda->status == 'direncanakan') text-yellow-600 dark:text-yellow-400
                    @elseif($agenda->status == 'berlangsung') text-green-600 dark:text-green-400
                    @elseif($agenda->status == 'selesai') text-blue-600 dark:text-blue-400
                    @else text-gray-600 dark:text-gray-400 @endif"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        @if($agenda->status == 'direncanakan') 📅 Agenda Direncanakan
                        @elseif($agenda->status == 'berlangsung') 🟢 Agenda Sedang Berlangsung
                        @elseif($agenda->status == 'selesai') ✅ Agenda Selesai
                        @else ❌ Agenda Dibatalkan @endif
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        @if($agenda->status == 'direncanakan') Agenda belum dimulai
                        @elseif($agenda->status == 'berlangsung') Agenda sedang berjalan
                        @elseif($agenda->status == 'selesai') Agenda telah selesai dilaksanakan
                        @else Agenda dibatalkan @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Judul -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $agenda->judul }}</h2>
            </div>

            <!-- Informasi Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <i class="fas fa-calendar-alt text-emerald-600 dark:text-emerald-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <i class="fas fa-clock text-emerald-600 dark:text-emerald-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Waktu</p>
                        <p class="font-medium text-gray-800 dark:text-white">
                            {{ date('H:i', strtotime($agenda->waktu_mulai)) }} WIB
                            @if($agenda->waktu_selesai)
                                - {{ date('H:i', strtotime($agenda->waktu_selesai)) }} WIB
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <i class="fas fa-map-marker-alt text-emerald-600 dark:text-emerald-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tempat</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $agenda->tempat }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <i class="fas fa-building text-emerald-600 dark:text-emerald-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Penyelenggara</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $agenda->penyelenggara ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <i class="fas fa-tag text-emerald-600 dark:text-emerald-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kategori</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $agenda->kategori ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <i class="fas fa-users text-emerald-600 dark:text-emerald-400 mt-1"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah Peserta</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $agenda->pegawai->count() }} orang</p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            @if($agenda->deskripsi)
            <div class="mb-6">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-2">
                    <i class="fas fa-align-left mr-2 text-emerald-600 dark:text-emerald-400"></i>
                    Deskripsi
                </h3>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <p class="text-gray-700 dark:text-gray-300">{{ $agenda->deskripsi }}</p>
                </div>
            </div>
            @endif

            <!-- Catatan -->
            @if($agenda->catatan)
            <div class="mb-6">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-2">
                    <i class="fas fa-sticky-note mr-2 text-emerald-600 dark:text-emerald-400"></i>
                    Catatan
                </h3>
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <p class="text-gray-700 dark:text-gray-300">{{ $agenda->catatan }}</p>
                </div>
            </div>
            @endif

            <!-- Daftar Peserta -->
            <div class="mt-6">
                <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">
                    <i class="fas fa-users mr-2 text-emerald-600 dark:text-emerald-400"></i>
                    Daftar Peserta ({{ $agenda->pegawai->count() }} orang)
                </h3>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama Pegawai</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Unit Kerja</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($agenda->pegawai as $peserta)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $peserta->nama_pegawai }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $peserta->unit_kerja }}</td>
                                <td class="px-4 py-3">
                                    @if($peserta->kehadiran == 'hadir')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fas fa-check-circle"></i> Hadir
                                        </span>
                                    @elseif($peserta->kehadiran == 'tidak_hadir')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <i class="fas fa-times-circle"></i> Tidak Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            <i class="fas fa-clock"></i> Belum Konfirmasi
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
    </div>
</div>

<!-- Modal Share -->
<div id="shareModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeShareModal()"></div>
    <div class="relative bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl mx-auto mt-20 p-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-share-alt text-green-500 mr-2"></i>
                Share Undangan
            </h3>
            <button onclick="closeShareModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <textarea id="shareText" rows="15"
            class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg font-mono text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white"
            readonly></textarea>
        <div class="flex justify-end gap-2 mt-4">
            <button onclick="copyShareText()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-lg">
                <i class="fas fa-copy mr-2"></i> Copy
            </button>
            <button onclick="shareViaWhatsApp()"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white rounded-lg">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </button>
            <button onclick="closeShareModal()"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 text-white rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Share function
document.querySelectorAll('.btn-share').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let btnElement = this;
        let originalHtml = btnElement.innerHTML;

        btnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btnElement.disabled = true;

        fetch('/agendas/' + id + '/share', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('shareText').value = data.text;
                document.getElementById('shareModal').classList.remove('hidden');
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error);
        })
        .finally(() => {
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
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
