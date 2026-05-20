@extends('layouts.app')

@section('title', 'Daftar Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
            Daftar Agenda
        </h1>
        <a href="{{ route('agendas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Tambah Agenda
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4 flex justify-between items-center shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600 dark:text-green-400"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="text-green-700 dark:text-green-300 hover:text-green-900 dark:hover:text-green-100" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4 flex justify-between items-center shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-600 dark:text-red-400"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="text-red-700 dark:text-red-300 hover:text-red-900 dark:hover:text-red-100" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
            <h3 class="font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-filter mr-2 text-blue-500"></i>
                Filter Pencarian
            </h3>
        </div>
        <div class="p-4">
            <form action="{{ route('agendas.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-gray-500"></i>
                            Tanggal Mulai
                        </label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-calendar-check mr-1 text-gray-500"></i>
                            Tanggal Akhir
                        </label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-tag mr-1 text-gray-500"></i>
                            Status
                        </label>
                        <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="direncanakan" {{ request('status') == 'direncanakan' ? 'selected' : '' }}>Direncanakan</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-search mr-1 text-gray-500"></i>
                            Pencarian
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/tempat..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('agendas.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                        <i class="fas fa-undo mr-1"></i>
                        Reset
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                        <i class="fas fa-filter mr-1"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Waktu Buat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tempat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($agendas as $agenda)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                                {{ $agenda->judul ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="far fa-calendar mr-2 text-gray-500"></i>
                                {{ $agenda->tanggal ? date('d/m/Y', strtotime($agenda->tanggal)) : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="far fa-clock mr-2 text-gray-500"></i>
                                {{ $agenda->waktu_mulai ? date('H:i', strtotime($agenda->waktu_mulai)) : '-' }} WIB
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-gray-500"></i>
                                {{ $agenda->created_at ? $agenda->created_at->format('d/m/Y H:i') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                {{ $agenda->tempat ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                <i class="fas fa-users mr-1"></i>
                                {{ optional($agenda->pegawai)->count() ?? 0 }} orang
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'direncanakan' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                    'berlangsung' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                                    'selesai' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                                    'dibatalkan' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
                                ];
                                $colorClass = $statusColors[$agenda->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
                            @endphp
                            <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                {{ ucfirst($agenda->status ?? 'unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <!-- 4 Icon Aksi -->
                            <div class="flex space-x-2">
                                <!-- Detail -->
                                <a href="{{ route('agendas.show', $agenda->id) }}"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg transition-colors duration-200"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('agendas.edit', $agenda->id) }}"
                                   class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg transition-colors duration-200"
                                   title="Edit Agenda">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Share -->
                                <button type="button"
                                        class="btn-share text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 bg-green-100 dark:bg-green-900/30 p-2 rounded-lg transition-colors duration-200"
                                        data-id="{{ $agenda->id }}"
                                        title="Share Undangan">
                                    <i class="fas fa-share-alt"></i>
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('agendas.destroy', $agenda->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-100 dark:bg-red-900/30 p-2 rounded-lg transition-colors duration-200"
                                            title="Hapus Agenda">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium mb-2">Belum ada data agenda</p>
                                <p class="text-sm text-gray-400 mb-4">Silakan tambah agenda baru untuk memulai</p>
                                <a href="{{ route('agendas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah agenda baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($agendas, 'links') && $agendas->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            {{ $agendas->withQueryString()->links() }}
        </div>
        @endif
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
// Share functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-share').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.dataset.id;
            let originalHtml = this.innerHTML;

            // Loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch('/agendas/' + id + '/share')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('shareText').value = data.text;
                        document.getElementById('shareModal').classList.remove('hidden');
                    } else {
                        alert('Gagal mengambil data share');
                    }
                })
                .catch(err => {
                    alert('Gagal mengambil data share');
                })
                .finally(() => {
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                });
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
