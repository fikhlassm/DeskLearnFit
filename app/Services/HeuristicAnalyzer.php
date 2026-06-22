<?php

namespace App\Services;

use App\Models\EntriNotebook;

class HeuristicAnalyzer
{
    /**
     * Indonesian stopwords — common particles, pronouns, and prepositions
     * that don't carry topical meaning.
     *
     * @var array<int, string>
     */
    private const STOPWORDS = [
        'yang', 'di', 'dan', 'adalah', 'ini', 'itu', 'dengan', 'untuk', 'dari', 'ke',
        'pada', 'dalam', 'tidak', 'ada', 'saya', 'kamu', 'dia', 'kami', 'kita', 'mereka',
        'akan', 'bisa', 'dapat', 'telah', 'sudah', 'belum', 'juga', 'saja', 'hanya',
        'atau', 'tapi', 'tetapi', 'namun', 'sebab', 'karena', 'jika', 'kalau', 'bila',
        'maka', 'oleh', 'seperti', 'yaitu', 'bahwa', 'antara', 'saat', 'ketika', 'waktu',
        'setelah', 'sebelum', 'sambil', 'tanpa', 'bagi', 'para', 'sang', 'si', 'nya',
        'lah', 'kah', 'pun', 'per', 'tahu', 'orang', 'hal', 'cara', 'bagaimana', 'apa',
        'siapa', 'kapan', 'dimana', 'mengapa', 'kenapa', 'ya', 'tidak', 'iya', 'oke',
        'ok', 'yaa', 'yuk', 'ayo', 'sebab',
    ];

    /**
     * Teaching/explanation markers in Indonesian, used as bonus for Feynman.
     *
     * @var array<int, string>
     */
    private const FEYNMAN_MARKERS = [
        'karena', 'sebab', 'misalnya', 'contoh', 'jadi', 'artinya', 'dengan kata lain',
        'yaitu', 'sehingga', 'oleh karena itu', 'dengan demikian', 'berarti',
        'maksudnya', 'simpulannya', 'kesimpulannya', 'jadi intinya', 'penjelasannya',
    ];

    /**
     * Analyze user-written content against the session topic.
     *
     * @return array{analisis: string, skor: int, kata_kunci_cocok: array<int, string>}
     */
    public function analyze(string $topik, string $konten, string $tipe): array
    {
        $kataKunci = $this->extractKeywords($topik);
        $kontenLower = mb_strtolower($konten);
        $kontenTokens = $this->tokenize($kontenLower);

        $kataCocok = [];
        foreach ($kataKunci as $kata) {
            if (in_array($kata, $kontenTokens, true)) {
                $kataCocok[] = $kata;
            }
        }

        $totalKataKunci = count($kataKunci);
        $jumlahCocok = count($kataCocok);

        $skor = 0;
        if ($totalKataKunci > 0) {
            $skor = (int) round(($jumlahCocok / $totalKataKunci) * 60);
        }

        $panjangKonten = count($kontenTokens);
        if ($panjangKonten >= 30) {
            $skor += 10;
        } elseif ($panjangKonten >= 15) {
            $skor += 5;
        }

        if ($tipe === EntriNotebook::TIPE_FEYNMAN) {
            $markersFound = $this->countMarkers($kontenLower, self::FEYNMAN_MARKERS);
            $skor += min($markersFound * 5, 25);
        }

        if ($tipe === EntriNotebook::TIPE_BLURTING) {
            if ($totalKataKunci > 0 && $jumlahCocok === $totalKataKunci) {
                $skor += 15;
            }
            if ($panjangKonten >= 50) {
                $skor += 10;
            }
        }

        $skor = max(0, min(100, $skor));

        $analisis = $this->buildNarasi(
            $tipe,
            $jumlahCocok,
            $totalKataKunci,
            $kataCocok,
            $panjangKonten,
            $skor,
        );

        return [
            'analisis' => $analisis,
            'skor' => $skor,
            'kata_kunci_cocok' => $kataCocok,
        ];
    }

    /**
     * Extract content keywords from a topic by removing stopwords.
     *
     * @return array<int, string>
     */
    private function extractKeywords(string $topik): array
    {
        $tokens = $this->tokenize($topik);

        $keywords = [];
        foreach ($tokens as $token) {
            if (strlen($token) < 3) {
                continue;
            }
            if (in_array($token, self::STOPWORDS, true)) {
                continue;
            }
            $keywords[] = $token;
        }

        return array_values(array_unique($keywords));
    }

    /**
     * Tokenize a string into lowercase word tokens. Keeps alphanumerics only.
     *
     * @return array<int, string>
     */
    private function tokenize(string $text): array
    {
        $normalized = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text) ?? '';
        $parts = preg_split('/\s+/u', mb_strtolower($normalized), -1, PREG_SPLIT_NO_EMPTY);

        return $parts ?: [];
    }

    /**
     * Count how many of the given markers appear in the text.
     *
     * @param  array<int, string>  $markers
     */
    private function countMarkers(string $text, array $markers): int
    {
        $count = 0;
        foreach ($markers as $marker) {
            if (str_contains($text, $marker)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Build a short Indonesian narrative summarizing the analysis.
     *
     * @param  array<int, string>  $kataCocok
     */
    private function buildNarasi(
        string $tipe,
        int $jumlahCocok,
        int $totalKataKunci,
        array $kataCocok,
        int $panjangKonten,
        int $skor,
    ): string {
        $label = $tipe === EntriNotebook::TIPE_FEYNMAN ? 'Feynman' : 'Blurting';

        $bagianCocok = $totalKataKunci > 0
            ? "Cocok {$jumlahCocok}/{$totalKataKunci} kata kunci topik"
            : 'Tidak ada kata kunci yang bisa diekstrak dari judul';

        $kataList = $kataCocok !== []
            ? ' ('.implode(', ', $kataCocok).')'
            : '';

        $panjang = "Panjang tulisan: {$panjangKonten} kata.";

        $saran = match (true) {
            $skor >= 80 => 'Kerja bagus! Penjelasan sudah sangat lengkap.',
            $skor >= 60 => 'Cukup baik. Coba tambahkan detail atau contoh.',
            $skor >= 40 => 'Perlu diperluas. Tambahkan kata kunci yang belum muncul.',
            default => 'Masih kurang. Coba jelaskan ulang dengan lebih lengkap.',
        };

        return "[{$label}] {$bagianCocok}{$kataList}. {$panjang} Skor: {$skor}/100. {$saran}";
    }
}
