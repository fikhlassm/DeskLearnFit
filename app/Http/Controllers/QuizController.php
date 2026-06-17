<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Show the quiz page.
     */
    public function show()
    {
        $user = Auth::user();

        // Jika sudah selesai quiz → langsung ke hasil
        if ($user->quiz_result) {
            return redirect()->route('quiz.result');
        }

        return view('quiz.quiz');
    }

    /**
     * Process submitted quiz answers.
     */
    public function submit(Request $request)
    {
        $answers = $request->input('answers', []);

        // Mapping jawaban ke skor metode
        $scoreMap = [
            'q1_visual'      => ['A' => 2, 'B' => 1],
            'q1_auditori'    => ['F' => 2, 'A' => 1],
            'q1_membaca'     => ['B' => 2, 'F' => 1],
            'q1_kinestetik'  => ['P' => 2, 'B' => 1],
            'q2_15menit'     => ['P' => 3],
            'q2_30menit'     => ['P' => 2, 'A' => 1],
            'q2_1jam'        => ['A' => 2, 'B' => 1],
            'q2_lebih'       => ['F' => 2, 'B' => 1],
            'q3_baca_ulang'  => ['B' => 2, 'F' => 1],
            'q3_rangkum'     => ['B' => 3],
            'q3_tanya'       => ['F' => 2, 'A' => 1],
            'q3_latihan'     => ['A' => 3],
            'q4_tenang'      => ['B' => 2, 'A' => 1],
            'q4_musik'       => ['P' => 2, 'F' => 1],
            'q4_ramai'       => ['F' => 2, 'P' => 1],
            'q4_alam'        => ['P' => 3],
            'q5_latihan'     => ['A' => 3],
            'q5_tutor'       => ['F' => 3],
            'q5_tulis_ulang' => ['B' => 3],
            'q5_jadwal'      => ['P' => 3],
            'q6_pagi'        => ['P' => 2, 'A' => 1],
            'q6_siang'       => ['F' => 2, 'B' => 1],
            'q6_sore'        => ['B' => 2, 'F' => 1],
            'q6_malam'       => ['A' => 2, 'P' => 1],
            'q7_ujian'       => ['A' => 2, 'P' => 1],
            'q7_pemahaman'   => ['F' => 3],
            'q7_skill'       => ['P' => 2, 'B' => 1],
            'q7_hafal'       => ['B' => 3],
        ];

        $scores = ['P' => 0, 'A' => 0, 'B' => 0, 'F' => 0];

        foreach ($answers as $questionKey => $optionValue) {
            $mapKey = $questionKey . '_' . $optionValue;
            if (isset($scoreMap[$mapKey])) {
                foreach ($scoreMap[$mapKey] as $method => $pts) {
                    $scores[$method] += $pts;
                }
            }
        }

        // Tentukan pemenang
        arsort($scores);
        $winner = array_key_first($scores);

        $methodMap = [
            'P' => 'pomodoro',
            'A' => 'active_recall',
            'B' => 'blurting',
            'F' => 'feynman',
        ];

        $result = $methodMap[$winner];

        // Simpan hasil DAN detail skor ke user
        $user = Auth::user();
        $user->quiz_result = $result;
        
        // Format skor agar sesuai dengan key di view (pomodoro, active_recall, dll)
        $formattedScores = [
            'pomodoro'      => $scores['P'],
            'active_recall' => $scores['A'],
            'blurting'      => $scores['B'],
            'feynman'       => $scores['F'],
        ];
        
        $user->quiz_scores = $formattedScores; 
        $user->save();

        return redirect()->route('quiz.result');
    }

    /**
     * Reset quiz result so user can retake it.
     */
    public function retake()
    {
        $user = Auth::user();
        $user->quiz_result = null;
        $user->quiz_scores = null;
        $user->save();

        return redirect()->route('quiz');
    }

    /**
     * Show the quiz result page.
     */
    public function result()
    {
        $user = Auth::user();

        if (!$user->quiz_result) {
            return redirect()->route('quiz');
        }

        // Ambil skor dari DB, fallback ke 0 jika belum ada data
        $scores = $user->quiz_scores ?? [
            'pomodoro'      => 0,
            'active_recall' => 0,
            'feynman'       => 0,
            'blurting'      => 0
        ];

        // Kirim KEDUA variabel ini ke view!
        return view('quiz.result', [
            'result' => $user->quiz_result,
            'scores' => $scores
        ]);
    }
}