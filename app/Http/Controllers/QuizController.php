<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Opsi valid per pertanyaan — harus sinkron dengan quiz.blade.php.
     *
     * @var array<string, array<int, string>>
     */
    private array $validOptions = [
        'q1' => ['visual', 'auditori', 'membaca', 'kinestetik'],
        'q2' => ['15menit', '30menit', '1jam', 'lebih'],
        'q3' => ['baca_ulang', 'rangkum', 'tanya', 'latihan'],
        'q4' => ['tenang', 'musik', 'ramai', 'alam'],
        'q5' => ['latihan', 'tutor', 'tulis_ulang', 'jadwal'],
        'q6' => ['pagi', 'siang', 'sore', 'malam'],
        'q7' => ['ujian', 'pemahaman', 'skill', 'hafal'],
    ];

    /**
     * Mapping jawaban ke skor metode belajar.
     * Key: "{question}_{option}"  Value: ['MethodCode' => points, ...]
     * P=Pomodoro, A=Active Recall, B=Blurting, F=Feynman
     *
     * @var array<string, array<string, int>>
     */
    private array $scoreMap = [
        'q1_visual' => ['A' => 2, 'B' => 1],
        'q1_auditori' => ['F' => 2, 'A' => 1],
        'q1_membaca' => ['B' => 2, 'F' => 1],
        'q1_kinestetik' => ['P' => 2, 'B' => 1],
        'q2_15menit' => ['P' => 3],
        'q2_30menit' => ['P' => 2, 'A' => 1],
        'q2_1jam' => ['A' => 2, 'B' => 1],
        'q2_lebih' => ['F' => 2, 'B' => 1],
        'q3_baca_ulang' => ['B' => 2, 'F' => 1],
        'q3_rangkum' => ['B' => 3],
        'q3_tanya' => ['F' => 2, 'A' => 1],
        'q3_latihan' => ['A' => 3],
        'q4_tenang' => ['B' => 2, 'A' => 1],
        'q4_musik' => ['P' => 2, 'F' => 1],
        'q4_ramai' => ['F' => 2, 'P' => 1],
        'q4_alam' => ['P' => 3],
        'q5_latihan' => ['A' => 3],
        'q5_tutor' => ['F' => 3],
        'q5_tulis_ulang' => ['B' => 3],
        'q5_jadwal' => ['P' => 3],
        'q6_pagi' => ['P' => 2, 'A' => 1],
        'q6_siang' => ['F' => 2, 'B' => 1],
        'q6_sore' => ['B' => 2, 'F' => 1],
        'q6_malam' => ['A' => 2, 'P' => 1],
        'q7_ujian' => ['A' => 2, 'P' => 1],
        'q7_pemahaman' => ['F' => 3],
        'q7_skill' => ['P' => 2, 'B' => 1],
        'q7_hafal' => ['B' => 3],
    ];

    /** Map kode metode ke nama hasil. */
    private array $methodMap = [
        'P' => 'pomodoro',
        'A' => 'active_recall',
        'B' => 'blurting',
        'F' => 'feynman',
    ];

    /** Tampilkan halaman quiz. Jika sudah punya hasil, redirect ke hasil. */
    public function show(): View|RedirectResponse
    {
        if (Auth::user()->quiz_result) {
            return redirect()->route('quiz.result');
        }

        return view('quiz.quiz');
    }

    /** Proses jawaban quiz yang dikirim. */
    public function submit(Request $request): RedirectResponse
    {
        // Buat aturan validasi dinamis dari $validOptions
        $rules = [];
        foreach ($this->validOptions as $qKey => $options) {
            $rules["answers.{$qKey}"] = ['required', 'string', 'in:'.implode(',', $options)];
        }

        $messages = [];
        foreach (array_keys($this->validOptions) as $qKey) {
            $num = ltrim($qKey, 'q');
            $messages["answers.{$qKey}.required"] = "Soal {$num} belum dijawab.";
            $messages["answers.{$qKey}.in"] = "Jawaban soal {$num} tidak valid.";
        }

        $validated = $request->validate($rules, $messages);
        $answers = $validated['answers'];

        // Hitung skor
        $scores = ['P' => 0, 'A' => 0, 'B' => 0, 'F' => 0];

        foreach ($answers as $questionKey => $optionValue) {
            $mapKey = $questionKey.'_'.$optionValue;
            if (isset($this->scoreMap[$mapKey])) {
                foreach ($this->scoreMap[$mapKey] as $method => $pts) {
                    $scores[$method] += $pts;
                }
            }
        }

        arsort($scores);
        $winner = array_key_first($scores);
        $result = $this->methodMap[$winner];

        $formattedScores = [
            'pomodoro' => $scores['P'],
            'active_recall' => $scores['A'],
            'blurting' => $scores['B'],
            'feynman' => $scores['F'],
        ];

        $user = Auth::user();
        $user->quiz_result = $result;
        $user->quiz_scores = $formattedScores;
        $user->save();

        return redirect()->route('quiz.result');
    }

    /** Reset hasil quiz agar siswa bisa mengulang. */
    public function retake(): RedirectResponse
    {
        $user = Auth::user();
        $user->quiz_result = null;
        $user->quiz_scores = null;
        $user->save();

        return redirect()->route('quiz');
    }

    /** Tampilkan halaman hasil quiz. */
    public function result(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user->quiz_result) {
            return redirect()->route('quiz');
        }

        $scores = $user->quiz_scores ?? [
            'pomodoro' => 0,
            'active_recall' => 0,
            'feynman' => 0,
            'blurting' => 0,
        ];

        return view('quiz.result', [
            'result' => $user->quiz_result,
            'scores' => $scores,
        ]);
    }
}
