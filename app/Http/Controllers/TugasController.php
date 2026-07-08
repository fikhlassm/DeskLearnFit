<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TugasController extends Controller
{
    // ── PENGAJAR ─────────────────────────────────────────────────────────────

    /** Daftar tugas per kelas. */
    public function index(Kelas $kelas): View
    {
        $this->authorizeKelas($kelas);

        $tugasList = $kelas->tugas()
            ->withCount('jawabanTugas')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dashboard.tugas.index', compact('kelas', 'tugasList'));
    }

    /** Simpan tugas baru. */
    public function store(Request $request, Kelas $kelas): RedirectResponse
    {
        $this->authorizeKelas($kelas);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'topik_id' => ['required', 'exists:topiks,id'],
            'deadline' => ['nullable', 'date', 'after:now'],
        ], [
            'deadline.after' => 'Deadline harus di masa depan.',
        ]);

        Tugas::create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => Auth::id(),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'topik_id' => $validated['topik_id'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'status' => 'terbit',
            'published_at' => now(),
        ]);

        return redirect()->route('kelas.show', $kelas)
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /** Form edit tugas. */
    public function edit(Tugas $tugas): View
    {
        $this->authorizeTugas($tugas);

        return view('dashboard.tugas.edit', compact('tugas'));
    }

    /** Update tugas. */
    public function update(Request $request, Tugas $tugas): RedirectResponse
    {
        $this->authorizeTugas($tugas);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'topik_id' => ['required', 'exists:topiks,id'],
            'deadline' => ['nullable', 'date'],
        ]);

        $tugas->update($validated);

        return redirect()->route('kelas.show', $tugas->kelas_id)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /** Hapus tugas. */
    public function destroy(Tugas $tugas): RedirectResponse
    {
        $this->authorizeTugas($tugas);
        $kelasId = $tugas->kelas_id;
        $tugas->delete();

        return redirect()->route('kelas.show', $kelasId)
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /** Publish tugas (draf → terbit). */
    public function publish(Tugas $tugas): RedirectResponse
    {
        $this->authorizeTugas($tugas);

        $tugas->update([
            'status' => 'terbit',
            'published_at' => now(),
        ]);

        return redirect()->route('kelas.show', $tugas->kelas_id)
            ->with('success', 'Tugas berhasil diterbitkan.');
    }

    // ── SISWA ─────────────────────────────────────────────────────────────────

    /** Daftar tugas terbit dari kelas yang diikuti siswa. */
    public function indexSiswa(Kelas $kelas): View
    {
        $this->authorizeSiswaKelas($kelas);

        $tugasList = $kelas->tugas()
            ->where('status', 'terbit')
            ->orderByDesc('deadline')
            ->paginate(15);

        // Ambil status jawaban siswa untuk setiap tugas
        $jawabanStatus = Auth::user()
            ->jawabanTugas()
            ->whereIn('tugas_id', $tugasList->pluck('id'))
            ->pluck('status', 'tugas_id');

        return view('dashboard.tugas.siswa-index', compact('kelas', 'tugasList', 'jawabanStatus'));
    }

    /** Detail tugas untuk siswa beserta form jawaban. */
    public function showSiswa(Tugas $tugas): View
    {
        if ($tugas->status === 'draf') {
            abort(404);
        }

        $this->authorizeSiswaKelas($tugas->kelas);

        $jawaban = Auth::user()
            ->jawabanTugas()
            ->where('tugas_id', $tugas->id)
            ->first();

        return view('dashboard.tugas.siswa-show', compact('tugas', 'jawaban'));
    }

    // ── Authorization helpers ─────────────────────────────────────────────────

    private function authorizeKelas(Kelas $kelas): void
    {
        if ($kelas->pengajar_id !== Auth::id()) {
            abort(403, 'Kelas ini bukan milik Anda.');
        }
    }

    private function authorizeTugas(Tugas $tugas): void
    {
        if ($tugas->pengajar_id !== Auth::id()) {
            abort(403, 'Tugas ini bukan milik Anda.');
        }
    }

    private function authorizeSiswaKelas(Kelas $kelas): void
    {
        $terdaftar = $kelas->siswa()->where('siswa_id', Auth::id())->exists();
        if (! $terdaftar) {
            abort(403, 'Kamu tidak terdaftar di kelas ini.');
        }
    }
}
