<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnggotaKelasController extends Controller
{
    /** Daftar kelas yang diikuti siswa + form join. */
    public function index(): View
    {
        $kelasDiikuti = Auth::user()
            ->kelasDiikuti()
            ->withCount('siswa')
            ->with('pengajar:id,name')
            ->latest('anggota_kelas.joined_at')
            ->get();

        return view('dashboard.kelas-diikuti', compact('kelasDiikuti'));
    }

    /** Tampilkan halaman detail kelas (Moodle style) untuk Siswa. */
    public function show(Kelas $kelas): View
    {
        // Pastikan siswa terdaftar di kelas ini
        $sudahJoin = AnggotaKelas::where('kelas_id', $kelas->id)
            ->where('siswa_id', Auth::id())
            ->exists();

        if (! $sudahJoin) {
            abort(403, 'Kamu tidak terdaftar di kelas ini.');
        }

        // Load relasi Topik beserta materi (published) dan tugas (published)
        $kelas->load(['topiks' => function ($query) {
            $query->orderBy('urutan', 'asc')->with([
                'materi' => function($q) { $q->where('status', 'terbit'); },
                'tugas' => function($q) { $q->where('status', 'terbit'); }
            ]);
        }]);

        // Cek materi & tugas general (uncategorized) yang sudah terbit
        $generalMateri = $kelas->materi()->whereNull('topik_id')->where('status', 'terbit')->get();
        $generalTugas = $kelas->tugas()->whereNull('topik_id')->where('status', 'terbit')->get();

        return view('dashboard.siswa.kelas-detail', compact('kelas', 'generalMateri', 'generalTugas'));
    }

    /** Siswa join kelas menggunakan kode_kelas. */
    public function join(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kelas' => ['required', 'string', 'max:20'],
        ], [
            'kode_kelas.required' => 'Kode kelas wajib diisi.',
        ]);

        $kelas = Kelas::where('kode_kelas', $validated['kode_kelas'])->first();

        if (! $kelas) {
            return back()->withErrors(['kode_kelas' => 'Kode kelas tidak ditemukan.'])->withInput();
        }

        if ($kelas->status !== 'aktif') {
            return back()->withErrors(['kode_kelas' => 'Kelas ini tidak aktif dan tidak bisa diikuti.'])->withInput();
        }

        $sudahJoin = AnggotaKelas::where('kelas_id', $kelas->id)
            ->where('siswa_id', Auth::id())
            ->exists();

        if ($sudahJoin) {
            return back()->withErrors(['kode_kelas' => 'Kamu sudah terdaftar di kelas ini.'])->withInput();
        }

        AnggotaKelas::create([
            'kelas_id' => $kelas->id,
            'siswa_id' => Auth::id(),
            'joined_at' => now(),
        ]);

        return redirect()->route('siswa.kelas.index')
            ->with('success', 'Berhasil bergabung ke kelas '.$kelas->nama_kelas.'!');
    }

    /** Siswa keluar dari kelas. */
    public function leave(Kelas $kelas): RedirectResponse
    {
        $anggota = AnggotaKelas::where('kelas_id', $kelas->id)
            ->where('siswa_id', Auth::id())
            ->first();

        if (! $anggota) {
            return redirect()->route('siswa.kelas.index')
                ->with('error', 'Kamu tidak terdaftar di kelas ini.');
        }

        $anggota->delete();

        return redirect()->route('siswa.kelas.index')
            ->with('success', 'Berhasil keluar dari kelas '.$kelas->nama_kelas.'.');
    }

    /** Pengajar melihat peserta kelas miliknya. */
    public function peserta(Kelas $kelas): View
    {
        if ($kelas->pengajar_id !== Auth::id()) {
            abort(403, 'Kelas ini bukan milik Anda.');
        }

        $peserta = $kelas->anggotaKelas()
            ->with('siswa:id,name,email')
            ->latest('joined_at')
            ->get();

        return view('dashboard.peserta-kelas', compact('kelas', 'peserta'));
    }
}
