<?php

namespace App\Http\Controllers;

use App\Models\JawabanTugas;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JawabanTugasController extends Controller
{
    // ── PENGAJAR: lihat semua jawaban satu tugas ──────────────────────────────

    public function index(Tugas $tugas): View
    {
        if ($tugas->pengajar_id !== Auth::id()) {
            abort(403, 'Tugas ini bukan milik Anda.');
        }

        $jawaban = $tugas->jawabanTugas()
            ->with('siswa:id,name,email')
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return view('dashboard.tugas.jawaban-index', compact('tugas', 'jawaban'));
    }

    // ── PENGAJAR: beri nilai ──────────────────────────────────────────────────

    public function nilai(Request $request, JawabanTugas $jawaban): RedirectResponse
    {
        if ($jawaban->tugas->pengajar_id !== Auth::id()) {
            abort(403, 'Jawaban ini bukan dari tugas milik Anda.');
        }

        $validated = $request->validate([
            'nilai' => ['required', 'integer', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ], [
            'nilai.min' => 'Nilai minimal 0.',
            'nilai.max' => 'Nilai maksimal 100.',
        ]);

        $jawaban->update([
            'nilai' => $validated['nilai'],
            'feedback' => $validated['feedback'] ?? null,
            'status' => 'dinilai',
        ]);

        return redirect()->route('tugas.jawaban.index', $jawaban->tugas_id)
            ->with('success', 'Nilai berhasil disimpan.');
    }

    // ── SISWA: submit jawaban ─────────────────────────────────────────────────

    public function submit(Request $request, Tugas $tugas): RedirectResponse
    {
        $this->authorizeSiswaKelas($tugas);

        if ($tugas->status === 'ditutup' || ($tugas->deadline && $tugas->deadline->isPast())) {
            return back()->with('error', 'Tugas ini sudah melewati batas waktu (deadline) dan otomatis ditutup.');
        }

        // Cek duplikasi
        $existing = JawabanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah mengumpulkan jawaban untuk tugas ini. Gunakan tombol edit untuk memperbarui.');
        }

        $validated = $request->validate([
            'tipe' => ['required', 'in:teks,link,file'],
            'jawaban_text' => ['nullable', 'string', 'max:10000'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'file_upload' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = null;
        if ($validated['tipe'] === 'file' && $request->hasFile('file_upload')) {
            $filePath = $request->file('file_upload')->store('jawaban_files', env('FILESYSTEM_DISK', 'public'));
        }

        $status = 'terkirim';
        if ($tugas->deadline && $tugas->deadline->isPast()) {
            $status = 'terlambat';
        }

        JawabanTugas::create([
            'tugas_id' => $tugas->id,
            'siswa_id' => Auth::id(),
            'tipe' => $validated['tipe'],
            'jawaban_text' => $validated['jawaban_text'] ?? null,
            'link_url' => $validated['tipe'] === 'link' ? ($validated['link_url'] ?? null) : null,
            'file_path' => $filePath,
            'submitted_at' => now(),
            'status' => $status,
        ]);

        $msg = $status === 'terlambat'
            ? 'Jawaban dikumpulkan (terlambat dari deadline).'
            : 'Jawaban berhasil dikumpulkan!';

        return redirect()->route('siswa.tugas.show', $tugas)
            ->with('success', $msg);
    }

    // ── SISWA: update jawaban ─────────────────────────────────────────────────

    public function updateSubmit(Request $request, JawabanTugas $jawaban): RedirectResponse
    {
        if ($jawaban->siswa_id !== Auth::id()) {
            abort(403, 'Jawaban ini bukan milik Anda.');
        }

        if ($jawaban->tugas->status === 'ditutup' || ($jawaban->tugas->deadline && $jawaban->tugas->deadline->isPast())) {
            return back()->with('error', 'Tugas ini sudah melewati batas waktu (deadline) dan otomatis ditutup, tidak bisa memperbarui jawaban.');
        }

        $validated = $request->validate([
            'tipe' => ['required', 'in:teks,link,file'],
            'jawaban_text' => ['nullable', 'string', 'max:10000'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'file_upload' => ['nullable', 'file', 'max:10240'],
        ]);

        $filePath = $jawaban->file_path;
        if ($validated['tipe'] === 'file' && $request->hasFile('file_upload')) {
            if ($filePath && \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($filePath);
            }
            $filePath = $request->file('file_upload')->store('jawaban_files', env('FILESYSTEM_DISK', 'public'));
        } elseif ($validated['tipe'] !== 'file') {
            $filePath = null;
        }

        $status = 'terkirim';
        if ($jawaban->tugas->deadline && $jawaban->tugas->deadline->isPast()) {
            $status = 'terlambat';
        }

        $jawaban->update([
            'tipe' => $validated['tipe'],
            'jawaban_text' => $validated['jawaban_text'] ?? null,
            'link_url' => $validated['tipe'] === 'link' ? ($validated['link_url'] ?? null) : null,
            'file_path' => $filePath,
            'submitted_at' => now(),
            'status' => $jawaban->status === 'dinilai' ? 'dinilai' : $status,
        ]);

        return redirect()->route('siswa.tugas.show', $jawaban->tugas_id)
            ->with('success', 'Jawaban berhasil diperbarui.');
    }

    private function authorizeSiswaKelas(Tugas $tugas): void
    {
        $terdaftar = $tugas->kelas->siswa()->where('siswa_id', Auth::id())->exists();
        if (! $terdaftar) {
            abort(403, 'Kamu tidak terdaftar di kelas ini.');
        }
    }
}
