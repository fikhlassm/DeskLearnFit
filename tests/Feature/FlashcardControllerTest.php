<?php

namespace Tests\Feature;

use App\Models\Flashcard;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashcardControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeSesi(User $user, string $metode = 'active_recall'): SesiBelajar
    {
        return SesiBelajar::factory()->create([
            'user_id' => $user->id,
            'metode' => $metode,
        ]);
    }

    public function test_siswa_bisa_tambah_kartu_di_sesi_miliknya(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/flashcards", [
                'pertanyaan' => 'Apa rumus turunan dari x kuadrat?',
                'jawaban' => '2x',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('flashcards', [
            'sesi_id' => $sesi->id,
            'pertanyaan' => 'Apa rumus turunan dari x kuadrat?',
            'jawaban' => '2x',
            'urutan' => 0,
        ]);
    }

    public function test_urutan_kartu_bertambah_otomatis(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($siswa)
                ->post("/dashboard/sesi-belajar/{$sesi->id}/flashcards", [
                    'pertanyaan' => "Soal {$i}?",
                    'jawaban' => "Jawaban {$i}",
                ]);
        }

        $this->assertDatabaseHas('flashcards', ['sesi_id' => $sesi->id, 'urutan' => 0]);
        $this->assertDatabaseHas('flashcards', ['sesi_id' => $sesi->id, 'urutan' => 1]);
        $this->assertDatabaseHas('flashcards', ['sesi_id' => $sesi->id, 'urutan' => 2]);
    }

    public function test_siswa_tidak_bisa_tambah_kartu_di_sesi_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/flashcards", [
                'pertanyaan' => 'P',
                'jawaban' => 'J',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('flashcards', 0);
    }

    public function test_tidak_bisa_tambah_kartu_di_sesi_non_active_recall(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa, 'pomodoro');

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/flashcards", [
                'pertanyaan' => 'P',
                'jawaban' => 'J',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseCount('flashcards', 0);
    }

    public function test_pertanyaan_dan_jawaban_wajib_diisi(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/flashcards", [
                'pertanyaan' => '',
                'jawaban' => '',
            ])
            ->assertSessionHasErrors(['pertanyaan', 'jawaban']);
    }

    public function test_siswa_bisa_update_kartu_milik_sendiri(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $card = Flashcard::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->put("/dashboard/flashcards/{$card->id}", [
                'pertanyaan' => 'Updated Q',
                'jawaban' => 'Updated A',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('flashcards', [
            'id' => $card->id,
            'pertanyaan' => 'Updated Q',
            'jawaban' => 'Updated A',
        ]);
    }

    public function test_siswa_tidak_bisa_update_kartu_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);
        $card = Flashcard::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->put("/dashboard/flashcards/{$card->id}", [
                'pertanyaan' => 'X',
                'jawaban' => 'Y',
            ])
            ->assertForbidden();
    }

    public function test_siswa_bisa_hapus_kartu_milik_sendiri(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $card = Flashcard::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->delete("/dashboard/flashcards/{$card->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('flashcards', ['id' => $card->id]);
    }

    public function test_siswa_tidak_bisa_hapus_kartu_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);
        $card = Flashcard::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->delete("/dashboard/flashcards/{$card->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('flashcards', ['id' => $card->id]);
    }

    public function test_kartu_cascade_delete_saat_sesi_dihapus(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        Flashcard::factory()->count(3)->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->delete("/dashboard/sesi-belajar/{$sesi->id}")
            ->assertRedirect();

        $this->assertDatabaseCount('flashcards', 0);
    }
}
