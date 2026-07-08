<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MateriController extends Controller
{
    // ── PENGAJAR ─────────────────────────────────────────────────────────────

    /** Daftar materi per kelas milik pengajar. */
    public function index(Kelas $kelas): View
    {
        $this->authorizeKelas($kelas);

        $materiList = $kelas->materi()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dashboard.materi.index', compact('kelas', 'materiList'));
    }

    /** Simpan materi baru. */
    public function store(Request $request, Kelas $kelas): RedirectResponse
    {
        $this->authorizeKelas($kelas);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'topik_id' => ['required', 'exists:topiks,id'],
            'tipe' => ['required', 'in:teks,link,file'],
            'link_url' => ['nullable', 'url', 'max:500', 'required_if:tipe,link'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,zip'],
        ], [
            'link_url.required_if' => 'URL wajib diisi jika tipe materi adalah link.',
        ]);

        $filePath = null;
        if ($request->hasFile('file') && $request->tipe === 'file') {
            $filePath = $request->file('file')->store('materi', 'public');
        }

        Materi::create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => Auth::id(),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'topik_id' => $validated['topik_id'] ?? null,
            'tipe' => $validated['tipe'],
            'link_url' => $validated['link_url'] ?? null,
            'file_path' => $filePath,
            'status' => 'terbit',
            'published_at' => now(),
        ]);

        return redirect()->route('kelas.show', $kelas)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /** Form edit materi. */
    public function edit(Materi $materi): View
    {
        $this->authorizeMateri($materi);

        return view('dashboard.materi.edit', compact('materi'));
    }

    /** Update materi. */
    public function update(Request $request, Materi $materi): RedirectResponse
    {
        $this->authorizeMateri($materi);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'topik_id' => ['required', 'exists:topiks,id'],
            'tipe' => ['required', 'in:teks,link,file'],
            'link_url' => ['nullable', 'url', 'max:500', 'required_if:tipe,link'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,zip'],
        ]);

        $filePath = $materi->file_path;
        if ($request->hasFile('file') && $request->tipe === 'file') {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('materi', 'public');
        }

        $materi->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'topik_id' => $validated['topik_id'] ?? $materi->topik_id,
            'tipe' => $validated['tipe'],
            'link_url' => $validated['link_url'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()->route('kelas.show', $materi->kelas_id)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /** Hapus materi. */
    public function destroy(Materi $materi): RedirectResponse
    {
        $this->authorizeMateri($materi);
        $kelasId = $materi->kelas_id;

        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('kelas.show', $kelasId)
            ->with('success', 'Materi berhasil dihapus.');
    }

    /** Publish materi (ubah status dari draf ke terbit). */
    public function publish(Materi $materi): RedirectResponse
    {
        $this->authorizeMateri($materi);

        $materi->update([
            'status' => 'terbit',
            'published_at' => now(),
        ]);

        return redirect()->route('kelas.show', $materi->kelas_id)
            ->with('success', 'Materi berhasil diterbitkan.');
    }

    // ── SISWA ─────────────────────────────────────────────────────────────────

    /** Daftar materi terbit dari kelas yang diikuti siswa. */
    public function indexSiswa(Kelas $kelas): View
    {
        $this->authorizeSiswaKelas($kelas);

        $materiList = $kelas->materi()
            ->where('status', 'terbit')
            ->orderByDesc('published_at')
            ->paginate(15);

        return view('dashboard.materi.siswa-index', compact('kelas', 'materiList'));
    }

    /** Detail materi terbit untuk siswa. */
    public function showSiswa(Materi $materi): View
    {
        if ($materi->status !== 'terbit') {
            abort(404);
        }

        $this->authorizeSiswaKelas($materi->kelas);

        return view('dashboard.materi.siswa-show', compact('materi'));
    }

    /** Download file materi (dengan authorization siswa/pengajar). */
    public function download(Materi $materi)
    {
        $user = Auth::user();

        if ($materi->status !== 'terbit' && $materi->pengajar_id !== $user->id) {
            abort(404);
        }

        $isMyKelas = $user->isPengajar()
            ? $materi->pengajar_id === $user->id
            : $materi->kelas->siswa()->where('siswa_id', $user->id)->exists();

        abort_unless($isMyKelas, 403, 'Anda tidak punya akses ke materi ini.');

        if (! $materi->file_path || ! Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $materi->file_path,
            $materi->judul.'.'.pathinfo($materi->file_path, PATHINFO_EXTENSION),
        );
    }

    // ── Authorization helpers ─────────────────────────────────────────────────

    private function authorizeKelas(Kelas $kelas): void
    {
        if ($kelas->pengajar_id !== Auth::id()) {
            abort(403, 'Kelas ini bukan milik Anda.');
        }
    }

    private function authorizeMateri(Materi $materi): void
    {
        if ($materi->pengajar_id !== Auth::id()) {
            abort(403, 'Materi ini bukan milik Anda.');
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
