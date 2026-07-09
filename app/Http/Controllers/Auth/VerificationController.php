<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /** Tampilkan halaman "verifikasi email kamu". */
    public function show(): View
    {
        return view('auth.verify');
    }

    /** (Re)kirim email verifikasi. Di dev: tampilkan link langsung. */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route($this->dashboardRoute($request->user()));
        }

        $verificationUrl = $this->makeVerificationUrl($request->user());

        return back()->with('success', 'Link verifikasi telah dibuat. (Mode dev: '.$verificationUrl.')');
    }

    /** Verifikasi email dari link. */
    public function verify(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        if (! URL::hasValidSignature($request)) {
            abort(403, 'Link verifikasi kadaluarsa atau tidak valid.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()->route($this->dashboardRoute($user))
            ->with('success', 'Email berhasil diverifikasi.');
    }

    private function makeVerificationUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())],
        );
    }

    private function dashboardRoute(User $user): string
    {
        return $user->isPengajar() ? 'dashboard.pengajar' : 'dashboard.siswa';
    }
}
