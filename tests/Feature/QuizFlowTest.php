<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizFlowTest extends TestCase
{
    use RefreshDatabase;

    /** Jawaban valid untuk semua 7 soal. */
    private function validAnswers(): array
    {
        return [
            'answers' => [
                'q1' => 'visual',
                'q2' => '15menit',
                'q3' => 'rangkum',
                'q4' => 'tenang',
                'q5' => 'jadwal',
                'q6' => 'pagi',
                'q7' => 'pemahaman',
            ],
        ];
    }

    public function test_siswa_bisa_lihat_halaman_quiz(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/quiz')
            ->assertStatus(200);
    }

    public function test_siswa_yang_sudah_quiz_redirect_ke_hasil(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/quiz')
            ->assertRedirect(route('quiz.result'));
    }

    public function test_submit_valid_menyimpan_quiz_result_dan_scores(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/quiz', $this->validAnswers())
            ->assertRedirect(route('quiz.result'));

        $siswa->refresh();
        $this->assertNotNull($siswa->quiz_result);
        $this->assertContains($siswa->quiz_result, ['pomodoro', 'active_recall', 'blurting', 'feynman']);
        $this->assertNotNull($siswa->quiz_scores);
        $this->assertArrayHasKey('pomodoro', $siswa->quiz_scores);
        $this->assertArrayHasKey('active_recall', $siswa->quiz_scores);
        $this->assertArrayHasKey('blurting', $siswa->quiz_scores);
        $this->assertArrayHasKey('feynman', $siswa->quiz_scores);
    }

    public function test_submit_kosong_ditolak_dengan_validation_error(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/quiz', ['answers' => []])
            ->assertSessionHasErrors();
    }

    public function test_submit_jawaban_tidak_lengkap_ditolak(): void
    {
        $siswa = User::factory()->siswa()->create();

        // Hanya jawab 3 dari 7 soal
        $this->actingAs($siswa)
            ->post('/quiz', [
                'answers' => [
                    'q1' => 'visual',
                    'q2' => '15menit',
                    'q3' => 'rangkum',
                ],
            ])
            ->assertSessionHasErrors();
    }

    public function test_submit_opsi_tidak_valid_ditolak(): void
    {
        $siswa = User::factory()->siswa()->create();

        $answers = $this->validAnswers();
        $answers['answers']['q1'] = 'opsi_tidak_ada';

        $this->actingAs($siswa)
            ->post('/quiz', $answers)
            ->assertSessionHasErrors('answers.q1');
    }

    public function test_hasil_quiz_tampil_jika_sudah_quiz(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/quiz/hasil')
            ->assertStatus(200)
            ->assertViewHas('result', 'pomodoro')
            ->assertViewHas('scores');
    }

    public function test_hasil_quiz_redirect_ke_quiz_jika_belum_quiz(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/quiz/hasil')
            ->assertRedirect(route('quiz'));
    }

    public function test_retake_menghapus_quiz_result(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/quiz/ulang')
            ->assertRedirect(route('quiz'));

        $siswa->refresh();
        $this->assertNull($siswa->quiz_result);
        $this->assertNull($siswa->quiz_scores);
    }

    public function test_pengajar_tidak_bisa_akses_quiz(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/quiz')
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
