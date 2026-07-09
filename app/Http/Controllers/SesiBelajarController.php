<?php

namespace App\Http\Controllers;

use App\Models\SesiBelajar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SesiBelajarController extends Controller
{
    private array $metodeValid = [
        'pomodoro',
        'active_recall',
        'blurting',
        'feynman',
    ];

    /** Tampilkan halaman daftar sesi belajar & form buat sesi baru. */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $riwayat = $user->sesiBelajar()
            ->orderByDesc('created_at')
            ->paginate(10);

        $totalSelesai = $user->sesiBelajar()
            ->where('status', 'selesai')
            ->count();

        $selectedMetode = $request->query('metode');
        if ($user->quiz_result) {
            $selectedMetode = $user->quiz_result;
        } else {
            if (! in_array($selectedMetode, $this->metodeValid, true)) {
                $selectedMetode = 'pomodoro';
            }
        }

        return view('dashboard.sesi-belajar', compact(
            'riwayat',
            'totalSelesai',
            'selectedMetode'
        ) + ['active' => 'sesi']);
    }

    /** Buat sesi belajar baru. */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'metode' => ['required', 'string', Rule::in($this->metodeValid)],
            'judul' => ['nullable', 'string', 'max:200'],
            'durasi_fokus_menit' => ['nullable', 'integer', 'min:1', 'max:120'],
            'durasi_istirahat_menit' => ['nullable', 'integer', 'min:1', 'max:60'],
            'jumlah_siklus' => ['nullable', 'integer', 'min:1', 'max:10'],
        ], [
            'metode.required' => 'Metode belajar wajib dipilih.',
            'metode.in' => 'Metode belajar tidak valid.',
            'durasi_fokus_menit.integer' => 'Durasi fokus harus berupa angka.',
            'durasi_fokus_menit.min' => 'Durasi fokus minimal 1 menit.',
            'durasi_fokus_menit.max' => 'Durasi fokus maksimal 120 menit.',
            'jumlah_siklus.integer' => 'Jumlah siklus harus berupa angka.',
            'jumlah_siklus.min' => 'Jumlah siklus minimal 1.',
            'jumlah_siklus.max' => 'Jumlah siklus maksimal 10.',
        ]);

        if ($user->quiz_result && $validated['metode'] !== $user->quiz_result) {
            return back()->with('error', 'Kamu hanya dapat menggunakan metode ' . ucfirst(str_replace('_', ' ', $user->quiz_result)) . ' sesuai hasil kuis.');
        }

        if ($validated['metode'] === 'pomodoro') {
            $request->validate([
                'durasi_fokus_menit' => ['required', 'integer', 'min:1', 'max:120'],
                'durasi_istirahat_menit' => ['required', 'integer', 'min:1', 'max:60'],
                'jumlah_siklus' => ['required', 'integer', 'min:1', 'max:10'],
            ], [
                'durasi_fokus_menit.required' => 'Durasi fokus wajib diisi untuk metode Pomodoro.',
                'durasi_istirahat_menit.required' => 'Durasi istirahat wajib diisi untuk metode Pomodoro.',
                'jumlah_siklus.required' => 'Jumlah siklus wajib diisi untuk metode Pomodoro.',
            ]);
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'aktif';
        $validated['started_at'] = now();
        $validated['durasi_fokus_menit'] = $request->input('durasi_fokus_menit', 25);
        $validated['durasi_istirahat_menit'] = $request->input('durasi_istirahat_menit', 5);
        $validated['jumlah_siklus'] = $request->input('jumlah_siklus', 1);

        $sesi = SesiBelajar::create($validated);

        return redirect()->route('sesi.show', $sesi->id)
            ->with('success', 'Sesi belajar dimulai!');
    }

    /** Tampilkan halaman spesifik untuk sesi belajar yang sedang aktif / selesai. */
    public function show(SesiBelajar $sesi): View
    {
        $this->authorizeOwnership($sesi);
        $sesi->load(['flashcards', 'entriNotebook']);

        return view('dashboard.sesi-show', [
            'sesi' => $sesi,
            'active' => 'sesi',
        ]);
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
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        return redirect()->route('sesi.index')
            ->with('success', 'Sesi belajar selesai! Kerja bagus.');
    }

    /** Hapus sesi belajar. */
    public function destroy(SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        $metode = $sesi->metode;
        $sesi->delete();

        return redirect()->route('sesi.index', ['metode' => $metode])
            ->with('success', 'Sesi belajar dihapus.');
    }

    /** Simpan catatan singkat per sesi Pomodoro. */
    public function updateCatatan(Request $request, SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'catatan.max' => 'Catatan maksimal 2000 karakter.',
        ]);

        $sesi->update(['catatan' => $validated['catatan'] ?? null]);

        return back()->with('success', 'Catatan sesi disimpan.');
    }

    private function authorizeOwnership(SesiBelajar $sesi): void
    {
        if ($sesi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
