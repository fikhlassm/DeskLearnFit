<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\JawabanTugas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Dashboard siswa — data real dari database. */
    public function siswa(): View
    {
        $user = Auth::user();

        // Statistik personal
        $totalCatatan = $user->jurnalBelajar()->count();
        $totalSesiSelesai = $user->sesiBelajar()->where('status', 'selesai')->count();
        $totalDurasi = $user->sesiBelajar()
            ->where('status', 'selesai')
            ->sum('durasi_fokus_menit');

        // Kelas yang diikuti
        $kelasDiikuti = $user->kelasDiikuti()
            ->with('pengajar:id,name')
            ->withCount('siswa')
            ->get();

        // Tugas aktif dari kelas yang diikuti
        $kelasIds = $kelasDiikuti->pluck('id');

        $tugasAktif = Tugas::whereIn('kelas_id', $kelasIds)
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

        $totalSiswa = AnggotaKelas::whereIn(
            'kelas_id',
            $user->kelasDiajar()->pluck('id')
        )->count();

        $totalMateriTerbit = Materi::where('pengajar_id', $user->id)
            ->where('status', 'terbit')
            ->count();

        $totalTugasTerbit = Tugas::where('pengajar_id', $user->id)
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

        $tugasTerbaru = Tugas::where('pengajar_id', $user->id)
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

        $hariIni = now()->translatedFormat('l'); // Senin, Selasa, etc.
        $jadwalHariIni = \App\Models\Jadwal::whereHas('kelas', fn ($q) => $q->where('pengajar_id', $user->id))
            ->with('kelas:id,nama_kelas')
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
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
            'jadwalHariIni',
        ));
    }
}
