<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\SesiBelajar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlashcardController extends Controller
{
    /** Tambah kartu baru ke sesi Active Recall. */
    public function store(Request $request, SesiBelajar $sesi): RedirectResponse
    {
        $this->authorizeOwnership($sesi);

        if ($sesi->metode !== 'active_recall') {
            return back()->with('error', 'Kartu flash hanya bisa ditambahkan ke sesi Active Recall.');
        }

        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'min:3', 'max:1000'],
            'jawaban' => ['required', 'string', 'min:1', 'max:2000'],
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.min' => 'Pertanyaan minimal 3 karakter.',
            'jawaban.required' => 'Jawaban wajib diisi.',
        ]);

        $nextUrutan = (Flashcard::where('sesi_id', $sesi->id)->max('urutan') ?? -1) + 1;

        Flashcard::create([
            'sesi_id' => $sesi->id,
            'pertanyaan' => $validated['pertanyaan'],
            'jawaban' => $validated['jawaban'],
            'urutan' => $nextUrutan,
        ]);

        return back()->with('success', 'Kartu flash berhasil ditambahkan.');
    }

    /** Update kartu flash. */
    public function update(Request $request, Flashcard $flashcard): RedirectResponse
    {
        $this->authorizeOwnership($flashcard->sesi);

        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'min:3', 'max:1000'],
            'jawaban' => ['required', 'string', 'min:1', 'max:2000'],
        ]);

        $flashcard->update($validated);

        return back()->with('success', 'Kartu flash diperbarui.');
    }

    /** Hapus kartu flash. */
    public function destroy(Flashcard $flashcard): RedirectResponse
    {
        $sesi = $flashcard->sesi;
        $this->authorizeOwnership($sesi);

        $flashcard->delete();

        return back()->with('success', 'Kartu flash dihapus.');
    }

    /** Tampilkan halaman review interaktif. */
    public function review(SesiBelajar $sesi)
    {
        $this->authorizeOwnership($sesi);

        if ($sesi->metode !== 'active_recall') {
            return back()->with('error', 'Review hanya untuk sesi Active Recall.');
        }

        $cards = $sesi->flashcards()->orderBy('urutan')->get();

        if ($cards->isEmpty()) {
            return redirect()->route('sesi.index', ['metode' => 'active_recall'])
                ->with('error', 'Belum ada kartu flash untuk direview.');
        }

        $stats = $this->getReviewStats($sesi);

        return view('dashboard.flashcard-review', [
            'sesi' => $sesi,
            'cards' => $cards,
            'stats' => $stats,
        ]);
    }

    /** Submit jawaban review (benar/salah) untuk satu kartu. */
    public function answer(Request $request, SesiBelajar $sesi)
    {
        $this->authorizeOwnership($sesi);

        $validated = $request->validate([
            'flashcard_id' => ['required', 'integer', 'exists:flashcards,id'],
            'benar' => ['required', 'boolean'],
        ]);

        $flashcard = Flashcard::where('id', $validated['flashcard_id'])
            ->where('sesi_id', $sesi->id)
            ->firstOrFail();

        FlashcardReview::create([
            'flashcard_id' => $flashcard->id,
            'sesi_id' => $sesi->id,
            'user_id' => Auth::id(),
            'benar' => $validated['benar'],
            'reviewed_at' => now(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /** Statistik review per sesi. */
    public function stats(SesiBelajar $sesi)
    {
        $this->authorizeOwnership($sesi);

        return response()->json($this->getReviewStats($sesi));
    }

    private function getReviewStats(SesiBelajar $sesi): array
    {
        $reviews = $sesi->flashcardReviews();

        $total = (clone $reviews)->count();
        $benar = (clone $reviews)->where('benar', true)->count();
        $salah = (clone $reviews)->where('benar', false)->count();
        $percent = $total > 0 ? (int) round(($benar / $total) * 100) : 0;

        $perCard = $sesi->flashcards()
            ->withCount(['reviews as total_reviews', 'reviews as benar_count' => fn ($q) => $q->where('benar', true)])
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'pertanyaan' => $c->pertanyaan,
                'total' => $c->total_reviews,
                'benar' => $c->benar_count,
                'akurasi' => $c->total_reviews > 0 ? (int) round(($c->benar_count / $c->total_reviews) * 100) : 0,
            ])
            ->values()
            ->toArray();

        return [
            'total' => $total,
            'benar' => $benar,
            'salah' => $salah,
            'percent' => $percent,
            'per_card' => $perCard,
        ];
    }

    private function authorizeOwnership(SesiBelajar $sesi): void
    {
        if ($sesi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
