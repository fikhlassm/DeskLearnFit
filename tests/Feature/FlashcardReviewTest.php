<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashcardReviewTest extends TestCase
{
    use RefreshDatabase;

    private function makeSesi(User $user, string $metode = 'active_recall'): SesiBelajar
    {
        return SesiBelajar::factory()->create([
            'user_id' => $user->id,
            'metode' => $metode,
        ]);
    }

    private function makeCards(SesiBelajar $sesi, int $n = 3): Collection
    {
        return Flashcard::factory()->count($n)->create(['sesi_id' => $sesi->id]);
    }

    public function test_siswa_buka_halaman_review_dengan_kartu(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $this->makeCards($sesi);

        $this->actingAs($siswa)
            ->get("/dashboard/sesi-belajar/{$sesi->id}/review")
            ->assertStatus(200)
            ->assertViewIs('dashboard.flashcard-review')
            ->assertViewHas('sesi')
            ->assertViewHas('cards')
            ->assertViewHas('stats');
    }

    public function test_review_redirect_jika_sesi_bukan_active_recall(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa, 'pomodoro');

        $this->actingAs($siswa)
            ->get("/dashboard/sesi-belajar/{$sesi->id}/review")
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_review_redirect_jika_belum_ada_kartu(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->get("/dashboard/sesi-belajar/{$sesi->id}/review")
            ->assertRedirect(route('sesi.index', ['metode' => 'active_recall']))
            ->assertSessionHas('error');
    }

    public function test_tidak_bisa_review_sesi_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);
        $this->makeCards($sesi);

        $this->actingAs($siswa)
            ->get("/dashboard/sesi-belajar/{$sesi->id}/review")
            ->assertForbidden();
    }

    public function test_siswa_bisa_submit_jawaban_benar(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $card = $this->makeCards($sesi, 1)->first();

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/review/answer", [
                'flashcard_id' => $card->id,
                'benar' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('flashcard_reviews', [
            'flashcard_id' => $card->id,
            'sesi_id' => $sesi->id,
            'user_id' => $siswa->id,
            'benar' => 1,
        ]);
    }

    public function test_siswa_bisa_submit_jawaban_salah(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $card = $this->makeCards($sesi, 1)->first();

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/review/answer", [
                'flashcard_id' => $card->id,
                'benar' => 0,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('flashcard_reviews', [
            'flashcard_id' => $card->id,
            'benar' => 0,
        ]);
    }

    public function test_tidak_bisa_submit_review_dengan_flashcard_orang_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);
        $card = Flashcard::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/review/answer", [
                'flashcard_id' => $card->id,
                'benar' => 1,
            ])
            ->assertForbidden();
    }

    public function test_validasi_flashcard_id_wajib_ada(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/review/answer", [
                'benar' => 1,
            ])
            ->assertSessionHasErrors('flashcard_id');
    }

    public function test_validasi_benar_wajib_boolean(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($sesi->user ?? $siswa);
        $card = $this->makeCards($sesi, 1)->first();

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/review/answer", [
                'flashcard_id' => $card->id,
                'benar' => 'bukan-boolean',
            ])
            ->assertSessionHasErrors('benar');
    }

    public function test_stats_menghitung_total_benar_salah_dan_persen(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $card = $this->makeCards($sesi, 1)->first();

        FlashcardReview::factory()->count(3)->create([
            'sesi_id' => $sesi->id,
            'flashcard_id' => $card->id,
            'user_id' => $siswa->id,
            'benar' => true,
        ]);
        FlashcardReview::factory()->count(2)->create([
            'sesi_id' => $sesi->id,
            'flashcard_id' => $card->id,
            'user_id' => $siswa->id,
            'benar' => false,
        ]);

        $response = $this->actingAs($siswa)
            ->getJson("/dashboard/sesi-belajar/{$sesi->id}/review/stats");

        $response->assertStatus(200)
            ->assertJson([
                'total' => 5,
                'benar' => 3,
                'salah' => 2,
                'percent' => 60,
            ]);
    }

    public function test_pengajar_tidak_bisa_akses_review_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $this->makeCards($sesi);

        $this->actingAs($pengajar)
            ->get("/dashboard/sesi-belajar/{$sesi->id}/review")
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
