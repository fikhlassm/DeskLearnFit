<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /** Tampilkan form input email untuk reset. */
    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    /** Kirim link reset ke email (di dev, tampilkan link langsung). */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])
                ->withInput($request->only('email'));
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ],
        );

        // Kirim email reset password
        $user->sendPasswordResetNotification($token);

        return redirect()->route('login')
            ->with('success', 'Tautan untuk mereset kata sandi telah dikirim ke email Anda. Silakan periksa kotak masuk (atau folder spam).');
    }
}
