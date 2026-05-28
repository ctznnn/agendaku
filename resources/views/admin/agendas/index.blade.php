@extends('layouts.admin')

@section('title', 'Daftar Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
            Daftar Agenda
        </h1>
        <a href="{{ route('admin.agendas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
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
    <!-- Filter Section -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
        <h3 class="font-medium text-gray-700 dark:text-gray-300">
            <i class="fas fa-filter mr-2 text-emerald-500"></i>
            Filter Pencarian
        </h3>
    </div>
    <div class="p-5">
        <form action="{{ route('admin.agendas.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Tanggal Acara -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-calendar-alt mr-1 text-gray-500"></i>
                        Tanggal Acara
                    </label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Status -->
                <div>
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
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>❌ Batal</option>
                    </select>
                </div>

                <!-- Pencarian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <i class="fas fa-search mr-1 text-gray-500"></i>
                        Cari Agenda
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Judul / Tempat / Penyelenggara..."
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Reset -->
            <div class="mt-4 flex justify-end">
                <a href="{{ route('admin.agendas.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                    <i class="fas fa-times mr-1"></i> Reset Filter
                </a>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                            <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 dark:border-gray-600">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tempat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($agendas as $agenda)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <input type="checkbox" class="agenda-checkbox rounded border-gray-300 dark:border-gray-600" value="{{ $agenda->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                                {{ \Illuminate\Support\Str::limit($agenda->judul, 50) ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="far fa-calendar mr-2 text-gray-500"></i>
                                {{ $agenda->tanggal ? \Carbon\Carbon::parse($agenda->tanggal)->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="far fa-clock mr-2 text-gray-500"></i>
                                @php
                                    $waktuMulai = $agenda->waktu_mulai;
                                    if (strpos($waktuMulai, ' ') !== false) {
                                        $parts = explode(' ', $waktuMulai);
                                        $waktuMulai = end($parts);
                                    }
                                    $waktuMulai = date('H:i', strtotime($waktuMulai));
                                @endphp
                                {{ $waktuMulai }} WIB
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                {{ \Illuminate\Support\Str::limit($agenda->tempat, 30) ?? '-' }}
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
                                    'selesai' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
                                ];
                                $colorClass = $statusColors[$agenda->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
                                $statusText = [
                                    'direncanakan' => 'Direncanakan',
                                    'berlangsung' => 'Berlangsung',
                                    'selesai' => 'Selesai'
                                ];
                                $displayStatus = $statusText[$agenda->status] ?? ucfirst($agenda->status);
                            @endphp
                            <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.agendas.show', $agenda->id) }}"
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.agendas.edit', $agenda->id) }}"
                                   class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg"
                                   title="Edit Agenda">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn-share text-green-600 dark:text-green-400 hover:text-green-800 bg-green-100 dark:bg-green-900/30 p-2 rounded-lg"
                                        data-id="{{ $agenda->id }}"
                                        title="Share Undangan">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                                <form action="{{ route('admin.agendas.destroy', $agenda->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 bg-red-100 dark:bg-red-900/30 p-2 rounded-lg" title="Hapus Agenda">
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
                                <a href="{{ route('admin.agendas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
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
        <div class="relative bg-white dark:bg-gray-800 rounded-lg w-full max-w-2xl p-6 shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-share-alt text-green-500 mr-2"></i>
                    Share Undangan
                </h3>
                <button onclick="closeShareModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <textarea id="shareText" rows="15" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg font-mono text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white" readonly></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button onclick="copyShareText()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 inline-flex items-center">
                    <i class="fas fa-copy mr-2"></i>Copy
                </button>
                <button onclick="shareViaWhatsApp()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 inline-flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                </button>
                <button onclick="closeShareModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// SELECT ALL button
document.getElementById('selectAllBtn')?.addEventListener('click', function() {
    document.querySelectorAll('.agenda-checkbox').forEach(cb => {
        cb.checked = true;
    });
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) selectAllCheckbox.checked = true;
});

// DESELECT ALL button
document.getElementById('deselectAllBtn')?.addEventListener('click', function() {
    document.querySelectorAll('.agenda-checkbox').forEach(cb => {
        cb.checked = false;
    });
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) selectAllCheckbox.checked = false;
});

// SELECT ALL checkbox di header
const selectAllCheckbox = document.getElementById('selectAllCheckbox');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        let isChecked = this.checked;
        document.querySelectorAll('.agenda-checkbox').forEach(cb => {
            cb.checked = isChecked;
        });
    });
}

// Update header checkbox ketika ada perubahan
document.querySelectorAll('.agenda-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        let allChecked = document.querySelectorAll('.agenda-checkbox:checked').length === document.querySelectorAll('.agenda-checkbox').length;
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
    });
});

// ==================== SINGLE SHARE (Admin) ====================
document.querySelectorAll('.btn-share').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let originalHtml = this.innerHTML;
        let btnElement = this;

        btnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btnElement.disabled = true;

        // ✅ URL untuk Admin
        let url = '/admin/agendas/' + id + '/share';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('shareText').value = data.text;
                document.getElementById('shareModal').classList.remove('hidden');
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Gagal mengambil data share. Silakan coba lagi.');
        })
        .finally(() => {
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
        });
    });
});

// ==================== BULK SHARE (Admin) ====================
const bulkShareBtn = document.getElementById('bulkShareBtn');
if (bulkShareBtn) {
    bulkShareBtn.addEventListener('click', function() {
        let selectedIds = [];
        document.querySelectorAll('.agenda-checkbox:checked').forEach(cb => {
            selectedIds.push(cb.value);
        });

        if (selectedIds.length === 0) {
            alert('Pilih minimal 1 agenda yang akan di-share');
            return;
        }

        document.getElementById('shareText').value = 'Loading...';
        document.getElementById('shareModal').classList.remove('hidden');

        // ✅ URL untuk Admin
        let promises = selectedIds.map(id => {
            return fetch('/admin/agendas/' + id + '/share', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json());
        });

        Promise.all(promises)
            .then(results => {
                let allText = '';
                results.forEach((data, index) => {
                    if (data.success) {
                        let text = data.text;

                        if (index === 0) {
                            allText += text;
                        } else {
                            let lines = text.split('\n');
                            lines.splice(0, 2);
                            let cleanedText = lines.join('\n');
                            allText += '\n\n================================\n\n';
                            allText += cleanedText;
                        }
                    }
                });
                document.getElementById('shareText').value = allText || 'Gagal membuat undangan';
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('shareText').value = 'Error: ' + err.message;
            });
    });
}

// ==================== FUNGSI SHARE ====================
function copyShareText() {
    let textarea = document.getElementById('shareText');
    textarea.select();
    textarea.setSelectionRange(0, 99999);
    document.execCommand('copy');
    alert('✅ Teks berhasil disalin ke clipboard!');
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
