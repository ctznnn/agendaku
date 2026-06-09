@extends('layouts.admin')

@section('title', 'Daftar Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-calendar-alt mr-2 text-emerald-600 dark:text-emerald-400"></i>
                Daftar Agenda
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua agenda kegiatan</p>
        </div>
        <div class="flex gap-2">
            <button type="button" id="bulkShareBtn" 
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-share-alt"></i>
                Share Terpilih
            </button>
            <a href="{{ route('admin.agendas.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-plus"></i>
                Tambah Agenda
            </a>
        </div>
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

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Agenda</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $agendas->total() }}</p>
                </div>
                <i class="fas fa-calendar-alt text-emerald-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Direncanakan</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ \App\Models\Agenda::where('status', 'direncanakan')->count() }}</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Berlangsung</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Agenda::where('status', 'berlangsung')->count() }}</p>
                </div>
                <i class="fas fa-play-circle text-green-500 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Agenda::where('status', 'selesai')->count() }}</p>
                </div>
                <i class="fas fa-check-circle text-blue-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h3 class="font-medium text-gray-700 dark:text-gray-300">
                <i class="fas fa-sliders-h mr-2 text-emerald-500"></i>
                Filter Pencarian
            </h3>
        </div>
        <div class="p-5">
            <form action="{{ route('admin.agendas.index') }}" method="GET">
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                placeholder="Tanggal"
                                class="pl-10 pr-3 py-2 w-44 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <select name="status"
                                class="pl-10 pr-8 py-2 w-44 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white appearance-none">
                                <option value="">Semua Status</option>
                                <option value="direncanakan" {{ request('status') == 'direncanakan' ? 'selected' : '' }}>📅 Direncanakan</option>
                                <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>🟢 Berlangsung</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>❌ Batal</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari agenda (judul/tempat/penyelenggara)..."
                                class="pl-10 pr-3 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.agendas.index') }}"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Bulk Action -->
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Centang agenda yang ingin di-share ke WhatsApp
                    </span>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="selectAllBtn" 
                        class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm transition">
                        <i class="fas fa-check-square mr-1"></i> Pilih Semua
                    </button>
                    <button type="button" id="deselectAllBtn" 
                        class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm transition">
                        <i class="fas fa-square mr-1"></i> Hapus Pilih
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($agendas as $agenda)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition overflow-hidden relative group">
            <!-- Checkbox -->
            <div class="absolute top-3 left-3 z-10">
                <input type="checkbox" class="agenda-checkbox w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500" value="{{ $agenda->id }}">
            </div>
            
            <!-- Status Bar -->
            <div class="h-1 
                @if($agenda->status == 'direncanakan') bg-yellow-500
                @elseif($agenda->status == 'berlangsung') bg-green-500
                @elseif($agenda->status == 'selesai') bg-blue-500
                @else bg-gray-500 @endif">
            </div>
            
            <div class="p-5 pl-10">
                <!-- Badge Baru -->
                @if($agenda->created_at->diffInHours(now()) < 24)
                <div class="mb-2">
                    <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                        <i class="fas fa-star mr-1"></i> Baru
                    </span>
                </div>
                @endif
                
                <div class="flex justify-between items-start mb-3">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($agenda->status == 'direncanakan') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                        @elseif($agenda->status == 'berlangsung') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @elseif($agenda->status == 'selesai') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 @endif">
                        @if($agenda->status == 'direncanakan') 📅 Direncanakan
                        @elseif($agenda->status == 'berlangsung') 🟢 Berlangsung
                        @elseif($agenda->status == 'selesai') ✅ Selesai
                        @else ❌ Batal @endif
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $agenda->created_at->diffForHumans() }}</span>
                </div>
                
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-2">{{ $agenda->judul }}</h3>
                
                @if($agenda->penyelenggara)
                <div class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-envelope mr-1"></i> {{ $agenda->penyelenggara }}
                </div>
                @endif
                
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-alt w-4 text-gray-400"></i>
                        <span>{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d F Y') }}</span>
                    </div>
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
                    @if($agenda->kategori)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-tag w-4 text-gray-400"></i>
                        <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700">
                            {{ $agenda->kategori }}
                        </span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users w-4 text-gray-400"></i>
                        <span>{{ $agenda->pegawai->count() }} Peserta</span>
                    </div>
                </div>
                
                <div class="flex justify-end gap-2 pt-3 border-t dark:border-gray-700">
                    <a href="{{ route('admin.agendas.show', $agenda->id) }}" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.agendas.edit', $agenda->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn-share p-2 text-green-600 hover:bg-green-100 rounded-lg transition" data-id="{{ $agenda->id }}" title="Share">
                        <i class="fas fa-share-alt"></i>
                    </button>
                    <form action="{{ route('admin.agendas.destroy', $agenda->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus agenda ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-calendar-times text-5xl text-gray-400 mb-3 block"></i>
            <p class="text-gray-500">Belum ada agenda</p>
            <a href="{{ route('admin.agendas.create') }}" class="mt-3 inline-block text-emerald-600 hover:underline">Buat agenda pertama</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $agendas->withQueryString()->links() }}
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
        <textarea id="shareText" rows="15" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg font-mono text-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white" readonly></textarea>
        <div class="flex justify-end gap-2 mt-4">
            <button onclick="copyShareText()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                <i class="fas fa-copy mr-2"></i> Copy
            </button>
            <button onclick="shareViaWhatsApp()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </button>
            <button onclick="closeShareModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                Tutup
            </button>
        </div>
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

<script>
// Select All
document.getElementById('selectAllBtn')?.addEventListener('click', function() {
    document.querySelectorAll('.agenda-checkbox').forEach(cb => cb.checked = true);
});

// Deselect All
document.getElementById('deselectAllBtn')?.addEventListener('click', function() {
    document.querySelectorAll('.agenda-checkbox').forEach(cb => cb.checked = false);
});

// Single Share
document.querySelectorAll('.btn-share').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let originalHtml = this.innerHTML;
        let btnElement = this;

        btnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btnElement.disabled = true;

        fetch('/admin/agendas/' + id + '/share', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('shareText').value = data.text;
                document.getElementById('shareModal').classList.remove('hidden');
                let encodedText = encodeURIComponent(data.text);
                window.open('https://wa.me/?text=' + encodedText, '_blank');
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            alert('Gagal mengambil data share');
        })
        .finally(() => {
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
        });
    });
});

// Bulk Share
document.getElementById('bulkShareBtn')?.addEventListener('click', function() {
    let selectedIds = [];
    document.querySelectorAll('.agenda-checkbox:checked').forEach(cb => {
        selectedIds.push(cb.value);
    });

    if (selectedIds.length === 0) {
        alert('Pilih minimal 1 agenda yang akan di-share');
        return;
    }

    if (!confirm(`Share ${selectedIds.length} agenda ke WhatsApp?`)) {
        return;
    }

    fetch('/admin/agendas/bulk-share', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('shareText').value = data.text;
            let encodedText = encodeURIComponent(data.text);
            window.open('https://wa.me/?text=' + encodedText, '_blank');
            document.getElementById('shareModal').classList.remove('hidden');
        } else {
            alert('Gagal: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        alert('Gagal: ' + err.message);
    });
});

function copyShareText() {
    let textarea = document.getElementById('shareText');
    textarea.select();
    document.execCommand('copy');
    alert('✅ Teks berhasil disalin!');
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
