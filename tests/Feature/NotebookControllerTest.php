<?php

namespace Tests\Feature;

use App\Models\EntriNotebook;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotebookControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeSesi(User $user, string $metode = 'blurting'): SesiBelajar
    {
        return SesiBelajar::factory()->create([
            'user_id' => $user->id,
            'metode' => $metode,
            'judul' => 'Fotosintesis Tumbuhan',
        ]);
    }

    public function test_siswa_bisa_submit_entri_blurting_dengan_analisis(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa, 'blurting');

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/notebook", [
                'konten' => 'Fotosintesis adalah proses tumbuhan membuat makanan dari cahaya matahari dan air.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $entri = EntriNotebook::where('sesi_id', $sesi->id)->first();
        $this->assertNotNull($entri);
        $this->assertSame('blurting', $entri->tipe);
        $this->assertNotNull($entri->analisis_sistem);
        $this->assertNotNull($entri->skor_keyakinan);
        $this->assertIsArray($entri->kata_kunci_cocok);
    }

    public function test_siswa_bisa_submit_entri_feynman_dengan_marker_bonus(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesiBlurting = $this->makeSesi($siswa, 'blurting');
        $sesiFeynman = $this->makeSesi($siswa, 'feynman');

        $konten = 'Fotosintesis pada tumbuhan memerlukan cahaya matahari. Misalnya daun hijau menangkap cahaya. Jadi artinya energi matahari diubah jadi makanan.';

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesiBlurting->id}/notebook", ['konten' => $konten]);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesiFeynman->id}/notebook", ['konten' => $konten]);

        $skorBlurting = EntriNotebook::where('sesi_id', $sesiBlurting->id)->value('skor_keyakinan');
        $skorFeynman = EntriNotebook::where('sesi_id', $sesiFeynman->id)->value('skor_keyakinan');

        $this->assertGreaterThanOrEqual($skorBlurting, $skorFeynman);

        $entriFeynman = EntriNotebook::where('sesi_id', $sesiFeynman->id)->first();
        $this->assertStringContainsString('Feynman', $entriFeynman->analisis_sistem);
    }

    public function test_tidak_bisa_submit_di_sesi_pomodoro(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa, 'pomodoro');

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/notebook", [
                'konten' => 'Saya sedang belajar.',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseCount('entri_notebook', 0);
    }

    public function test_tidak_bisa_submit_di_sesi_active_recall(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa, 'active_recall');

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/notebook", [
                'konten' => 'Konten tidak valid.',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseCount('entri_notebook', 0);
    }

    public function test_siswa_tidak_bisa_submit_di_sesi_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain, 'blurting');

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/notebook", [
                'konten' => 'Saya tidak punya akses.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('entri_notebook', 0);
    }

    public function test_konten_wajib_diisi_minimal_5_karakter(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/notebook", [
                'konten' => 'abc',
            ])
            ->assertSessionHasErrors('konten');
    }

    public function test_entri_cascade_delete_saat_sesi_dihapus(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        EntriNotebook::factory()->count(2)->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->delete("/dashboard/sesi-belajar/{$sesi->id}")
            ->assertRedirect();

        $this->assertDatabaseCount('entri_notebook', 0);
    }

    public function test_siswa_bisa_hapus_entri_milik_sendiri(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $entri = EntriNotebook::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->delete("/dashboard/notebook/{$entri->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('entri_notebook', ['id' => $entri->id]);
    }

    public function test_siswa_tidak_bisa_hapus_entri_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);
        $entri = EntriNotebook::factory()->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->delete("/dashboard/notebook/{$entri->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('entri_notebook', ['id' => $entri->id]);
    }

    public function test_kata_kunci_cocok_tersimpan_sebagai_array(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($sesi->user ?? $siswa, 'feynman');

        $this->actingAs($siswa)
            ->post("/dashboard/sesi-belajar/{$sesi->id}/notebook", [
                'konten' => 'Fotosintesis pada tumbuhan memerlukan cahaya matahari untuk membuat makanan.',
            ]);

        $entri = EntriNotebook::where('sesi_id', $sesi->id)->first();
        $this->assertIsArray($entri->kata_kunci_cocok);
        $this->assertNotEmpty($entri->kata_kunci_cocok);
        $this->assertContains('fotosintesis', $entri->kata_kunci_cocok);
    }
}
