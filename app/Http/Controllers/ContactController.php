<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'topik' => 'required|string|max:50',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        $text = "Anda menerima pesan baru dari form kontak LearnFit:\n\n";
        $text .= "Nama: " . $validated['nama'] . "\n";
        $text .= "Telepon: " . ($validated['telepon'] ?? '-') . "\n";
        $text .= "Topik: " . $validated['topik'] . "\n";
        $text .= "Subjek: " . $validated['subjek'] . "\n\n";
        $text .= "Pesan:\n" . $validated['pesan'];

        try {
            Mail::raw($text, function ($message) use ($validated) {
                $message->to('arafiramadan18012000@gmail.com')
                        ->subject('Pesan Kontak LearnFit: ' . $validated['subjek']);
            });
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
