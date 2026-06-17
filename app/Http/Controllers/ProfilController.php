<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /** Tampilkan halaman profil user (siswa atau pengajar). */
    public function show(): View
    {
        $user = Auth::user();

        return view('dashboard.profil', compact('user'));
    }

    /** Update data profil. Role tidak bisa diubah melalui request ini. */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio'           => ['nullable', 'string', 'max:1000'],
            'tujuan_belajar' => ['nullable', 'string', 'max:500'],
            'jenjang'       => ['nullable', 'string', 'max:100'],
            'no_hp'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
            'no_hp.regex'    => 'Format nomor HP tidak valid.',
        ]);

        // Pastikan role tidak ikut ter-update walau dikirim lewat request
        $user->fill($validated);
        $user->save();

        return redirect()->route('profil.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
