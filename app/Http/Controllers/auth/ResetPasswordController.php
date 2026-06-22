<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /** Tampilkan form reset password (input password baru). */
    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Proses reset password. */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kadaluarsa.'])
                ->withInput($request->only('email'));
        }

        if (! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset tidak valid.'])
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.'])
                ->withInput($request->only('email'));
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Auth::login($user);

        $route = $user->isPengajar() ? 'dashboard.pengajar' : 'dashboard.siswa';

        return redirect()->route($route)
            ->with('success', 'Password berhasil direset. Anda sudah login.');
    }
}
