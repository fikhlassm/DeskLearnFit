<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimoniController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        Testimoni::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'is_tampil' => true,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Testimoni Anda berhasil dikirim.');
    }
}
