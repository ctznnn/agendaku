@extends('layouts.admin')

@section('title', 'Edit Agenda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-edit mr-2 text-yellow-600 dark:text-yellow-400"></i>
            Edit Agenda
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.agendas.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="button" class="btn-share-agenda px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition" data-id="{{ $agenda->id }}">
                <i class="fas fa-share-alt mr-2"></i> Share Agenda
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-lg">
            <strong>Error!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('admin.agendas.update', $agenda->id) }}" method="POST" id="agendaForm">
            @csrf
            @method('PUT')

            <div class="p-6">
                <!-- Informasi Agenda -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Judul Agenda <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul', $agenda->judul) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $agenda->tanggal) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', date('H:i', strtotime($agenda->waktu_mulai))) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Waktu Selesai
                        </label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $agenda->waktu_selesai ? date('H:i', strtotime($agenda->waktu_selesai)) : '') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tempat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat" value="{{ old('tempat', $agenda->tempat) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Penyelenggara
                        </label>
                        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $agenda->penyelenggara) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Kategori - Input Manual -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            <i class="fas fa-tag mr-1 text-gray-500"></i>
                            Kategori
                        </label>
                        <input type="text" name="kategori" value="{{ old('kategori', $agenda->kategori) }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Contoh: Rapat, Sosialisasi, Pelatihan, dll">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="direncanakan" {{ old('status', $agenda->status) == 'direncanakan' ? 'selected' : '' }}>📅 Direncanakan</option>
                            <option value="berlangsung" {{ old('status', $agenda->status) == 'berlangsung' ? 'selected' : '' }}>🟢 Berlangsung</option>
                            <option value="selesai" {{ old('status', $agenda->status) == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                            <option value="batal" {{ old('status', $agenda->status) == 'batal' ? 'selected' : '' }}>❌ Batal</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Masukkan deskripsi agenda">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan
                        </label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Masukkan catatan tambahan">{{ old('catatan', $agenda->catatan) }}</textarea>
                    </div>
                </div>

                <!-- Daftar Pegawai -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        <i class="fas fa-users mr-2 text-emerald-600"></i>
                        Daftar Pegawai <span class="text-red-500">*</span>
                    </h2>

                    @if(isset($pegawaiList) && $pegawaiList->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto p-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/30">
                            @foreach($pegawaiList as $pegawai)
                                <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition">
                                    <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}"
                                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500"
                                        {{ in_array($pegawai->id, $selectedPegawai ?? []) ? 'checked' : '' }}>
                                    <span class="ml-3">
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $pegawai->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ $pegawai->unit_kerja ?? 'Unit Kerja' }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-700">
                            <i class="fas fa-users text-4xl text-gray-400 mb-2 block"></i>
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data pegawai</p>
                            <a href="{{ route('admin.pegawai.create') }}" class="inline-block mt-2 text-emerald-600 hover:underline">Tambah pegawai dulu</a>
                        </div>
                    @endif
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.agendas.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                        <i class="fas fa-save mr-1"></i> Update Agenda
                    </button>
                </div>
            </div>
        </form>
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
            <button onclick="copyShareText()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <i class="fas fa-copy mr-2"></i> Copy
            </button>
            <button onclick="shareViaWhatsApp()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
            </button>
            <button onclick="closeShareModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Share agenda dari tombol di header
document.querySelector('.btn-share-agenda')?.addEventListener('click', function() {
    let id = this.dataset.id;
    let btn = this;
    let originalHtml = btn.innerHTML;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

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
        } else {
            alert('Gagal: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Gagal mengambil data share');
    })
    .finally(() => {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
});

// Validasi form
document.getElementById('agendaForm').addEventListener('submit', function(e) {
    let checkboxes = document.querySelectorAll('input[name="pegawai_ids[]"]:checked');
    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 pegawai!');
    }
});

function copyShareText() {
    let textarea = document.getElementById('shareText');
    textarea.select();
    textarea.setSelectionRange(0, 99999);
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
