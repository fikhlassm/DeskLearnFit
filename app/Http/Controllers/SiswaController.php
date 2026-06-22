<?php

namespace App\Http\Controllers;

use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /** Tampilkan daftar semua siswa yang pernah mengikuti kelas pengajar. */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $kelasIds = $user->kelasDiajar()->pluck('id');

        $siswaQuery = User::where('role', 'siswa')
            ->whereHas('anggotaKelas', fn ($q) => $q->whereIn('kelas_id', $kelasIds))
            ->withCount([
                'sesiBelajar as total_sesi',
                'jurnalBelajar as total_jurnal',
                'sesiBelajar as total_sesi_selesai' => fn ($q) => $q->where('status', 'selesai'),
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $siswa = $siswaQuery->orderBy('name')->paginate(15);

        $siswa->getCollection()->transform(function ($s) {
            $s->kelas_diikuti = $s->kelasDiikuti()
                ->whereIn('kelas.id', Auth::user()->kelasDiajar()->pluck('id'))
                ->pluck('nama_kelas')
                ->toArray();

            return $s;
        });

        return view('dashboard.pengajar.siswa-index', [
            'siswa' => $siswa,
        ]);
    }

    /** Tampilkan profil lengkap satu siswa. */
    public function show(User $siswa): View
    {
        $user = Auth::user();

        abort_unless($siswa->isSiswa(), 404, 'User bukan siswa.');

        $isMyStudent = $siswa->kelasDiikuti()
            ->whereIn('kelas.id', $user->kelasDiajar()->pluck('id'))
            ->exists();

        abort_unless($isMyStudent, 403, 'Siswa ini tidak mengikuti kelas Anda.');

        $sesiByMetode = SesiBelajar::where('user_id', $siswa->id)
            ->where('status', 'selesai')
            ->selectRaw('metode, count(*) as total, sum(durasi_fokus_menit) as total_durasi')
            ->groupBy('metode')
            ->get();

        $totalSesi = SesiBelajar::where('user_id', $siswa->id)->count();
        $totalSelesai = SesiBelajar::where('user_id', $siswa->id)->where('status', 'selesai')->count();
        $totalDurasi = SesiBelajar::where('user_id', $siswa->id)->where('status', 'selesai')->sum('durasi_fokus_menit');
        $totalJurnal = $siswa->jurnalBelajar()->count();
        $totalKelas = $siswa->kelasDiikuti()
            ->whereIn('kelas.id', $user->kelasDiajar()->pluck('id'))
            ->count();

        $sesiTerbaru = SesiBelajar::where('user_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.pengajar.siswa-show', [
            'siswa' => $siswa,
            'sesiByMetode' => $sesiByMetode,
            'totalSesi' => $totalSesi,
            'totalSelesai' => $totalSelesai,
            'totalDurasi' => $totalDurasi,
            'totalJurnal' => $totalJurnal,
            'totalKelas' => $totalKelas,
            'sesiTerbaru' => $sesiTerbaru,
        ]);
    }
}
