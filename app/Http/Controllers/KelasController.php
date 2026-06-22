<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KelasController extends Controller
{
    /** Tampilkan kelas milik pengajar yang login saja. */
    public function index(): View
    {
        $kelasList = Kelas::where('pengajar_id', Auth::id())
            ->withCount('siswa')
            ->latest()
            ->get();

        return view('dashboard.kelas-saya', compact('kelasList'));
    }

    /** Buat kelas baru; pengajar_id diisi dari session, bukan dari form. */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:100'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'kode_kelas' => ['required', 'string', 'max:20', 'unique:kelas,kode_kelas'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:200'],
            'status' => ['required', 'in:aktif,draf,selesai'],
        ], [
            'kode_kelas.unique' => 'Kode kelas sudah digunakan.',
        ]);

        $validated['pengajar_id'] = Auth::id();

        Kelas::create($validated);

        return redirect()->route('dashboard.kelas')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    /** Kembalikan data kelas sebagai JSON untuk modal edit. */
    public function edit(Kelas $kelas): JsonResponse
    {
        $this->authorizeOwnership($kelas);

        return response()->json($kelas);
    }

    /** Update kelas milik pengajar ini. */
    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $this->authorizeOwnership($kelas);

        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:100'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'kode_kelas' => ['required', 'string', 'max:20', 'unique:kelas,kode_kelas,'.$kelas->id],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:200'],
            'status' => ['required', 'in:aktif,draf,selesai'],
        ], [
            'kode_kelas.unique' => 'Kode kelas sudah digunakan oleh kelas lain.',
        ]);

        $kelas->update($validated);

        return redirect()->route('dashboard.kelas')
            ->with('success', 'Kelas berhasil diperbarui!');
    }

    /** Hapus kelas milik pengajar ini. */
    public function destroy(Kelas $kelas): RedirectResponse
    {
        $this->authorizeOwnership($kelas);

        $kelas->delete();

        return redirect()->route('dashboard.kelas')
            ->with('success', 'Kelas berhasil dihapus!');
    }

    /** Pastikan kelas milik pengajar yang sedang login. */
    private function authorizeOwnership(Kelas $kelas): void
    {
        if ($kelas->pengajar_id !== Auth::id()) {
            abort(403, 'Kelas ini bukan milik Anda.');
        }
    }
}
