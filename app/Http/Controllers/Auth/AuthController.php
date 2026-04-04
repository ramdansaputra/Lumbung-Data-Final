<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password; // Wajib diaktifkan untuk mengirim email reset
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman lupa password dengan data desa
     */
    public function showLupaPassword()
    {
        // Mengambil data identitas desa dari database untuk logo & nama
        $desa = DB::table('identitas_desa')->first();

        return view('auth.lupa', compact('desa'));
    }

    /**
     * Proses Mengirim Link Reset Password ke Email
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi input email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.exists'   => 'Email tidak terdaftar di sistem kami.',
        ]);

        // Mengirim email reset password menggunakan fitur bawaan Laravel
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Mengecek apakah email berhasil dikirim
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda! Silakan cek kotak masuk atau folder spam.');
        }

        // Jika gagal (misalnya karena masalah koneksi SMTP)
        return back()->withErrors(['email' => 'Terjadi kesalahan. Gagal mengirim email reset password.']);
    }

    /**
     * Menampilkan Form Reset Password (saat link di email diklik)
     */
    public function showResetPassword(Request $request, $token)
    {
        $desa = DB::table('identitas_desa')->first();
        
        // Menampilkan view reset password dengan membawa token dan email dari link
        return view('auth.reset', [
            'token' => $token,
            'email' => $request->email,
            'desa'  => $desa
        ]);
    }

    /**
     * Proses Mengganti Password ke Database
     */
    public function resetPassword(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed', // Wajib ada input password_confirmation di form
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Proses reset menggunakan fitur bawaan Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        // 3. Cek apakah berhasil
        if ($status == Password::PASSWORD_RESET) {
            // Jika sukses, lempar kembali ke halaman login dengan pesan sukses
            return redirect()->route('login')->with('status', 'Password Anda berhasil direset! Silakan login dengan password baru.');
        }

        // Jika gagal (misal token kadaluarsa)
        return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
    }
}