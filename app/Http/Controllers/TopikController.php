<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Topik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopikController extends Controller
{
    private function authorizeOwnership(Kelas $kelas): void
    {
        if ($kelas->pengajar_id !== Auth::id()) {
            abort(403, 'Kelas ini bukan milik Anda.');
        }
    }

    public function store(Request $request, Kelas $kelas)
    {
        $this->authorizeOwnership($kelas);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
        ]);

        $urutan = $kelas->topiks()->max('urutan') + 1;
        $validated['kelas_id'] = $kelas->id;
        $validated['urutan'] = $urutan;

        Topik::create($validated);

        return back()->with('success', 'Topik berhasil ditambahkan.');
    }

    public function update(Request $request, Topik $topik)
    {
        $this->authorizeOwnership($topik->kelas);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
        ]);

        $topik->update($validated);

        return back()->with('success', 'Topik berhasil diperbarui.');
    }

    public function destroy(Topik $topik)
    {
        $this->authorizeOwnership($topik->kelas);
        $topik->delete();

        return back()->with('success', 'Topik berhasil dihapus.');
    }

    public function reorder(Request $request, Kelas $kelas)
    {
        $this->authorizeOwnership($kelas);
        $order = $request->input('order'); // array of topik IDs
        if (is_array($order)) {
            foreach ($order as $index => $id) {
                Topik::where('id', $id)->where('kelas_id', $kelas->id)->update(['urutan' => $index]);
            }
        }
        return response()->json(['status' => 'success']);
    }
}
