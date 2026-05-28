<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerifyNewEmail;

class ProfileController extends Controller
{
    // HAPUS这个方法 atau ubah seperti ini:
    // public function __construct()
    // {
    //     $this->middleware('auth'); // ← HAPUS baris ini di Laravel 11+
    // }

    // Di Laravel 11+, middleware diatur di routes, bukan di controller

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = ['name' => 'required|string|max:255'];

        if ($user->isPegawai()) {
            $rules['unit_kerja'] = 'nullable|string|max:255';
        }

        $request->validate($rules);
        $user->update($request->only(['name', 'unit_kerja']));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function showChangeEmailForm()
    {
        return view('profile.change-email');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        $token = Str::random(64);
        $user->update([
            'new_email' => $request->new_email,
            'new_email_token' => $token,
        ]);

        Mail::to($request->new_email)->send(new VerifyNewEmail($user, $token));

        return back()->with('info', 'Link verifikasi telah dikirim ke email baru Anda.');
    }

    public function verifyNewEmail($token)
    {
        $user = Auth::user();

        if (!$user || $user->new_email_token !== $token) {
            return redirect('/profile/email')->withErrors(['token' => 'Token verifikasi tidak valid.']);
        }

        $user->update([
            'email' => $user->new_email,
            'new_email' => null,
            'new_email_token' => null,
            'email_verified_at' => now(),
        ]);

        return redirect('/profile/email')->with('success', 'Email berhasil diubah.');
    }

    public function resendVerification()
    {
        $user = Auth::user();

        if ($user->new_email && $user->new_email_token) {
            Mail::to($user->new_email)->send(new VerifyNewEmail($user, $user->new_email_token));
            return back()->with('info', 'Link verifikasi telah dikirim ulang.');
        }

        return back()->with('error', 'Tidak ada permintaan perubahan email.');
    }
}
