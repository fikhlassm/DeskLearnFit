<?php

namespace App\Http\Controllers;

use App\Models\SesiBelajar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SesiBelajarController extends Controller
{
    private array $metodeValid = [
        'pomodoro',
        'active_recall',
        'blurting',
        'feynman',
    ];

    /** Tampilkan halaman sesi belajar + riwayat. */
    public function index(): View
    {
        $user = Auth::user();

        $sesiAktif = $user->sesiBelajar()
            ->where('status', 'aktif')
            ->whereNotNull('started_at')
            ->latest()
            ->first();

        $riwayat = $user->sesiBelajar()
            ->orderByDesc('created_at')
            ->paginate(10);

        $totalSelesai = $user->sesiBelajar()
            ->where('status', 'selesai')
            ->count();

        return view('dashboard.sesi-belajar', compact(
            'sesiAktif',
            'riwayat',
            'totalSelesai',
        ));
    }

    /** Buat sesi belajar baru. */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'metode'                 => ['required', 'string', 'in:' . implode(',', $this->metodeValid)],
            'judul'                  => ['nullable', 'string', 'max:200'],
            'durasi_fokus_menit'     => ['required', 'integer', 'min:1', 'max:120'],
            'durasi_istirahat_menit' => ['required', 'integer', 'min:1', 'max:60'],
            'jumlah_siklus'          => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'metode.required'            => 'Metode belajar wajib dipilih.',
            'durasi_fokus_menit.required' => 'Durasi fokus wajib diisi.',
            'durasi_fokus_menit.min'     => 'Durasi fokus minimal 1 menit.',
            'durasi_fokus_menit.max'     => 'Durasi fokus maksimal 120 menit.',
            'jumlah_siklus.min'          => 'Jumlah siklus minimal 1.',
            'jumlah_siklus.max'          => 'Jumlah siklus maksimal 10.',
        ]);

        $validated['user_id']    = Auth::id();
        $validated['status']     = 'aktif';
        $validated['started_at'] = now();

        SesiBelajar::create($validated);

        return redirect()->route('sesi.index')
            ->with('success', 'Sesi belajar dimulai! Fokus selama ' . $validated['durasi_fokus_menit'] . ' menit.');
    }

    /** Tandai sesi sebagai dimulai (set started_at). */
    public function start(SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        if ($sesi->status !== 'aktif') {
            return redirect()->route('sesi.index')
                ->with('error', 'Sesi ini tidak bisa dimulai.');
        }

        $sesi->update(['started_at' => now()]);

        return redirect()->route('sesi.index')
            ->with('success', 'Sesi belajar dimulai!');
    }

    /** Tandai sesi sebagai selesai. */
    public function complete(SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        if ($sesi->status !== 'aktif') {
            return redirect()->route('sesi.index')
                ->with('error', 'Sesi ini sudah selesai atau dibatalkan.');
        }

        $sesi->update([
            'status'       => 'selesai',
            'completed_at' => now(),
        ]);

        return redirect()->route('sesi.index')
            ->with('success', 'Sesi belajar selesai! Kerja bagus 🎉');
    }

    /** Hapus sesi belajar. */
    public function destroy(SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        $sesi->delete();

        return redirect()->route('sesi.index')
            ->with('success', 'Sesi belajar dihapus.');
    }

    private function authorizeOwnership(SesiBelajar $sesi): void
    {
        if ($sesi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
