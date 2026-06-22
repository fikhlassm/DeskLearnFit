<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /** Redirect ke halaman OAuth Google. */
    public function redirect()
    {
        if (! $this->googleConfigured()) {
            return redirect()->route('login')
                ->with('error', 'Google Sign-In belum dikonfigurasi. Hubungi admin.');
        }

        return Socialite::driver('google')->redirect();
    }

    /** Callback dari Google setelah user authorize. */
    public function callback(): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()->route('login')
                ->with('error', 'Google Sign-In belum dikonfigurasi. Hubungi admin.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Login Google gagal: '.$e->getMessage());
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'siswa',
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, true);

        $route = $user->isPengajar() ? 'dashboard.pengajar' : 'dashboard.siswa';

        return redirect()->route($route)
            ->with('success', 'Berhasil login dengan Google.');
    }

    private function googleConfigured(): bool
    {
        return ! empty(config('services.google.client_id'))
            && ! empty(config('services.google.client_secret'));
    }
}
