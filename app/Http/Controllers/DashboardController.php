<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\JawabanTugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Dashboard siswa — data real dari database. */
    public function siswa(): View
    {
        $user = Auth::user();

        // Statistik personal
        $totalCatatan    = $user->jurnalBelajar()->count();
        $totalSesiSelesai = $user->sesiBelajar()->where('status', 'selesai')->count();
        $totalDurasi     = $user->sesiBelajar()
            ->where('status', 'selesai')
            ->sum('durasi_fokus_menit');

        // Kelas yang diikuti
        $kelasDiikuti = $user->kelasDiikuti()
            ->with('pengajar:id,name')
            ->withCount('siswa')
            ->get();

        // Tugas aktif dari kelas yang diikuti
        $kelasIds = $kelasDiikuti->pluck('id');

        $tugasAktif = \App\Models\Tugas::whereIn('kelas_id', $kelasIds)
            ->where('status', 'terbit')
            ->with('kelas:id,nama_kelas')
            ->orderBy('deadline')
            ->get();

        // Jawaban yang sudah dikumpulkan
        $jawabanTerkumpul = $user->jawabanTugas()
            ->whereIn('tugas_id', $tugasAktif->pluck('id'))
            ->pluck('tugas_id')
            ->toArray();

        $tugasBelumKumpul = $tugasAktif->whereNotIn('id', $jawabanTerkumpul);
        $tugasSudahKumpul = $tugasAktif->whereIn('id', $jawabanTerkumpul);

        // Data terbaru
        $catatanTerbaru = $user->jurnalBelajar()
            ->latest('tanggal')
            ->first();

        $sesiTerbaru = $user->sesiBelajar()
            ->latest()
            ->first();

        return view('dashboard.siswa', compact(
            'user',
            'totalCatatan',
            'totalSesiSelesai',
            'totalDurasi',
            'kelasDiikuti',
            'tugasAktif',
            'tugasBelumKumpul',
            'tugasSudahKumpul',
            'catatanTerbaru',
            'sesiTerbaru',
        ));
    }

    /** Dashboard pengajar — data real dari database. */
    public function pengajar(): View
    {
        $user = Auth::user();

        // Statistik pengajar
        $totalKelas = $user->kelasDiajar()->count();

        $totalSiswa = \App\Models\AnggotaKelas::whereIn(
            'kelas_id',
            $user->kelasDiajar()->pluck('id')
        )->count();

        $totalMateriTerbit = \App\Models\Materi::where('pengajar_id', $user->id)
            ->where('status', 'terbit')
            ->count();

        $totalTugasTerbit = \App\Models\Tugas::where('pengajar_id', $user->id)
            ->where('status', 'terbit')
            ->count();

        $totalJawabanBelumDinilai = JawabanTugas::whereHas('tugas', fn ($q) => $q->where('pengajar_id', $user->id))
            ->where('status', 'terkirim')
            ->count();

        // Data terbaru
        $kelasTerbaru = $user->kelasDiajar()
            ->withCount('siswa')
            ->latest()
            ->take(3)
            ->get();

        $tugasTerbaru = \App\Models\Tugas::where('pengajar_id', $user->id)
            ->with('kelas:id,nama_kelas')
            ->withCount('jawabanTugas')
            ->latest()
            ->take(5)
            ->get();

        $jawabanTerbaru = JawabanTugas::whereHas('tugas', fn ($q) => $q->where('pengajar_id', $user->id))
            ->with(['siswa:id,name', 'tugas:id,judul'])
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('dashboard.pengajar', compact(
            'user',
            'totalKelas',
            'totalSiswa',
            'totalMateriTerbit',
            'totalTugasTerbit',
            'totalJawabanBelumDinilai',
            'kelasTerbaru',
            'tugasTerbaru',
            'jawabanTerbaru',
        ));
    }
}
