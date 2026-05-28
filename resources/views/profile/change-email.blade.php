@extends('layouts.app')

@section('title', 'Ganti Email')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
            <h1 class="text-xl font-bold text-white">Ganti Email</h1>
            <p class="text-emerald-100 text-sm">Email saat ini: <strong>{{ Auth::user()->email }}</strong></p>
        </div>

        @if(session('success'))
            <div class="m-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="m-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 text-sm">
                {{ session('info') }}
            </div>
        @endif

        @if(Auth::user()->new_email)
            <div class="mx-4 mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-clock mr-1"></i> Menunggu verifikasi email baru: <strong>{{ Auth::user()->new_email }}</strong>
                </p>
                <form method="POST" action="{{ route('profile.email.resend') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-sm text-emerald-600 hover:underline">Kirim ulang verifikasi</button>
                </form>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.email.update') }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Baru</label>
                <input type="email" name="new_email" value="{{ old('new_email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                    placeholder="email-baru@example.com">
                @error('new_email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                <p class="text-xs text-blue-600 dark:text-blue-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    Link verifikasi akan dikirim ke email baru Anda. Email tidak akan berubah sampai Anda mengklik link tersebut.
                </p>
            </div>

            <div class="flex justify-between items-center pt-3">
                <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-600">← Kembali</a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                    Kirim Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
