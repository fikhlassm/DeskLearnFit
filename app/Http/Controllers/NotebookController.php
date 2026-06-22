<?php

namespace App\Http\Controllers;

use App\Models\EntriNotebook;
use App\Models\SesiBelajar;
use App\Services\HeuristicAnalyzer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotebookController extends Controller
{
    public function __construct(private readonly HeuristicAnalyzer $analyzer) {}

    /** Tampilkan halaman "Notebook Saya" — grup sesi per metode. */
    public function index(): View
    {
        $user = Auth::user();

        $sesiGrouped = $user->sesiBelajar()
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('metode');

        return view('dashboard.notebook-index', [
            'sesiByMetode' => $sesiGrouped,
        ]);
    }

    /** Submit entri baru (Blurting atau Feynman) untuk dianalisis. */
    public function store(Request $request, SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        if (! in_array($sesi->metode, ['blurting', 'feynman'], true)) {
            return back()->with('error', 'Notebook hanya tersedia untuk sesi Blurting atau Feynman.');
        }

        $validated = $request->validate([
            'konten' => ['required', 'string', 'min:5', 'max:5000'],
        ], [
            'konten.required' => 'Konten entri wajib diisi.',
            'konten.min' => 'Konten entri minimal 5 karakter.',
        ]);

        $topik = $sesi->judul ?: $sesi->metode;
        $hasil = $this->analyzer->analyze($topik, $validated['konten'], $sesi->metode);

        EntriNotebook::create([
            'sesi_id' => $sesi->id,
            'tipe' => $sesi->metode,
            'konten' => $validated['konten'],
            'analisis_sistem' => $hasil['analisis'],
            'skor_keyakinan' => $hasil['skor'],
            'kata_kunci_cocok' => $hasil['kata_kunci_cocok'],
        ]);

        return back()->with('success', 'Entri tersimpan. Skor: '.$hasil['skor'].'/100.');
    }

    /** Hapus entri notebook. */
    public function destroy(EntriNotebook $entri): RedirectResponse
    {
        $this->authorizeOwnership($entri->sesi);

        $entri->delete();

        return back()->with('success', 'Entri dihapus.');
    }

    private function authorizeOwnership(SesiBelajar $sesi): void
    {
        if ($sesi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
