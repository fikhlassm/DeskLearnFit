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
            ->with('jadwals') // Load schedules
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
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'theme_color' => ['nullable', 'string', 'max:50'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:200'],
            'hari' => ['required', 'string', 'max:20'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruang' => ['required', 'string', 'max:100'],
        ]);

        $validated['pengajar_id'] = Auth::id();
        $validated['status'] = 'aktif'; // Default status
        
        do {
            $kode = strtoupper(\Illuminate\Support\Str::random(6));
        } while (Kelas::where('kode_kelas', $kode)->exists());
        $validated['kode_kelas'] = $kode;

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/covers'), $filename);
            $validated['cover_image'] = 'uploads/covers/' . $filename;
        }

        $kelas = Kelas::create($validated);

        // Buat jadwal default
        \App\Models\Jadwal::create([
            'kelas_id' => $kelas->id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruang' => $request->ruang,
        ]);

        return redirect()->route('dashboard.kelas')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    /** Tampilkan halaman detail kelas (Moodle style). */
    public function show(Kelas $kelas): View
    {
        $this->authorizeOwnership($kelas);

        // Load relasi Topik beserta materi dan tugasnya, urutkan berdasarkan urutan.
        $kelas->load(['topiks' => function ($query) {
            $query->orderBy('urutan', 'asc')->with(['materi', 'tugas']);
        }]);

        // Cek materi & tugas yang tidak punya topik (General / uncategorized)
        $generalMateri = $kelas->materi()->whereNull('topik_id')->get();
        $generalTugas = $kelas->tugas()->whereNull('topik_id')->get();

        return view('dashboard.pengajar.kelas-detail', compact('kelas', 'generalMateri', 'generalTugas'));
    }

    /** Update kelas milik pengajar ini. */
    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $this->authorizeOwnership($kelas);

        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:100'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:aktif,draf,selesai'],
            'theme_color' => ['nullable', 'string', 'max:50'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:200'],
            'hari' => ['required', 'string', 'max:20'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruang' => ['required', 'string', 'max:100'],
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus gambar lama jika ada
            if ($kelas->cover_image && file_exists(public_path($kelas->cover_image))) {
                unlink(public_path($kelas->cover_image));
            }
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/covers'), $filename);
            $validated['cover_image'] = 'uploads/covers/' . $filename;
        }

        $kelas->update($validated);

        // Update atau buat jadwal default
        \App\Models\Jadwal::updateOrCreate(
            ['kelas_id' => $kelas->id],
            [
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'ruang' => $request->ruang,
            ]
        );

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
