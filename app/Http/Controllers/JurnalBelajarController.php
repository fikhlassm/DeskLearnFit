<?php

namespace App\Http\Controllers;

use App\Models\JurnalBelajar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JurnalBelajarController extends Controller
{
    /** Daftar metode valid yang bisa dipilih siswa. */
    private array $metodeValid = [
        'pomodoro',
        'active_recall',
        'blurting',
        'feynman',
        'lainnya',
    ];

    /** Tampilkan daftar catatan belajar milik siswa yang login. */
    public function index(Request $request): View
    {
        $query = Auth::user()
            ->jurnalBelajar()
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at');

        // Jika user sudah memiliki hasil kuis, maka secara paksa metode diset menjadi hasil kuis.
        if (Auth::user()->quiz_result) {
            $query->where('metode_yang_digunakan', Auth::user()->quiz_result);
        } else {
            // Filter opsional berdasarkan metode
            if ($request->filled('metode') && in_array($request->metode, $this->metodeValid, true)) {
                $query->where('metode_yang_digunakan', $request->metode);
            }
        }

        $jurnalList = $query->paginate(10)->withQueryString();
        $totalJurnal = Auth::user()->jurnalBelajar()->count();
        $jurnalTerbaru = Auth::user()->jurnalBelajar()->latest('tanggal')->first();

        return view('dashboard.catatan-belajar', compact(
            'jurnalList',
            'totalJurnal',
            'jurnalTerbaru',
        ));
    }

    /** Simpan catatan belajar baru. */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'judul' => ['nullable', 'string', 'max:200'],
            'isi_jurnal' => ['required', 'string', 'max:5000'],
            'metode_yang_digunakan' => ['nullable', 'string', 'in:'.implode(',', $this->metodeValid)],
            'rating_efektivitas' => ['nullable', 'integer', 'min:1', 'max:5'],
            'durasi_menit' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
            'isi_jurnal.required' => 'Isi jurnal wajib diisi.',
            'rating_efektivitas.min' => 'Rating minimal 1.',
            'rating_efektivitas.max' => 'Rating maksimal 5.',
        ]);

        if ($user->quiz_result && $validated['metode_yang_digunakan'] !== $user->quiz_result) {
            return back()->with('error', 'Kamu hanya dapat membuat catatan dengan metode ' . ucfirst(str_replace('_', ' ', $user->quiz_result)) . ' sesuai hasil kuis.');
        }

        $user->jurnalBelajar()->create($validated);

        return redirect()->route('catatan.index')
            ->with('success', 'Catatan belajar berhasil disimpan.');
    }

    /** Tampilkan form edit catatan — dikembalikan sebagai JSON untuk modal. */
    public function edit(JurnalBelajar $jurnal): mixed
    {
        $this->authorizeOwnership($jurnal);

        // Format tanggal untuk input[type=date]
        return response()->json([
            'id' => $jurnal->id,
            'tanggal' => $jurnal->tanggal->format('Y-m-d'),
            'judul' => $jurnal->judul,
            'isi_jurnal' => $jurnal->isi_jurnal,
            'metode_yang_digunakan' => $jurnal->metode_yang_digunakan,
            'rating_efektivitas' => $jurnal->rating_efektivitas,
            'durasi_menit' => $jurnal->durasi_menit,
        ]);
    }

    /** Update catatan belajar. */
    public function update(Request $request, JurnalBelajar $catatan): RedirectResponse
    {
        $user = Auth::user();

        if ($catatan->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'judul' => ['nullable', 'string', 'max:200'],
            'isi_jurnal' => ['required', 'string', 'max:5000'],
            'metode_yang_digunakan' => ['nullable', 'string', 'in:'.implode(',', $this->metodeValid)],
            'rating_efektivitas' => ['nullable', 'integer', 'min:1', 'max:5'],
            'durasi_menit' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ]);

        if ($user->quiz_result && $validated['metode_yang_digunakan'] !== $user->quiz_result) {
            return back()->with('error', 'Kamu hanya dapat mengubah catatan ke metode ' . ucfirst(str_replace('_', ' ', $user->quiz_result)) . ' sesuai hasil kuis.');
        }

        $catatan->update($validated);

        return redirect()->route('catatan.index')
            ->with('success', 'Catatan belajar berhasil diperbarui.');
    }

    /** Hapus catatan belajar. */
    public function destroy(JurnalBelajar $jurnal): RedirectResponse
    {
        $this->authorizeOwnership($jurnal);

        $jurnal->delete();

        return redirect()->route('catatan.index')
            ->with('success', 'Catatan belajar berhasil dihapus.');
    }

    /** Pastikan jurnal yang diakses adalah milik user yang login. */
    private function authorizeOwnership(JurnalBelajar $jurnal): void
    {
        if ($jurnal->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
