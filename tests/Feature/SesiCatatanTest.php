<?php

namespace Tests\Feature;

use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SesiCatatanTest extends TestCase
{
    use RefreshDatabase;

    private function makeSesi(User $user): SesiBelajar
    {
        return SesiBelajar::factory()->create([
            'user_id' => $user->id,
            'metode' => 'pomodoro',
        ]);
    }

    public function test_siswa_bisa_simpan_catatan_per_sesi(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->patch("/dashboard/sesi-belajar/{$sesi->id}/catatan", [
                'catatan' => 'Hari ini aku belajar turunan parsial. Cukup challenging.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('sesi_belajar', [
            'id' => $sesi->id,
            'catatan' => 'Hari ini aku belajar turunan parsial. Cukup challenging.',
        ]);
    }

    public function test_siswa_bisa_update_catatan_yang_sudah_ada(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $sesi->update(['catatan' => 'Catatan lama']);

        $this->actingAs($siswa)
            ->patch("/dashboard/sesi-belajar/{$sesi->id}/catatan", [
                'catatan' => 'Catatan baru',
            ])
            ->assertRedirect();

        $sesi->refresh();
        $this->assertSame('Catatan baru', $sesi->catatan);
    }

    public function test_catatan_bisa_diatur_ke_null(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);
        $sesi->update(['catatan' => 'Akan dihapus']);

        $this->actingAs($siswa)
            ->patch("/dashboard/sesi-belajar/{$sesi->id}/catatan", [
                'catatan' => null,
            ])
            ->assertRedirect();

        $sesi->refresh();
        $this->assertNull($sesi->catatan);
    }

    public function test_catatan_lebih_2000_karakter_ditolak(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($siswa)
            ->patch("/dashboard/sesi-belajar/{$sesi->id}/catatan", [
                'catatan' => str_repeat('a', 2001),
            ])
            ->assertSessionHasErrors('catatan');
    }

    public function test_tidak_bisa_update_catatan_sesi_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($lain);

        $this->actingAs($siswa)
            ->patch("/dashboard/sesi-belajar/{$sesi->id}/catatan", [
                'catatan' => 'Saya tidak punya akses',
            ])
            ->assertForbidden();
    }

    public function test_pengajar_tidak_bisa_update_catatan_sesi_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $siswa = User::factory()->siswa()->create();
        $sesi = $this->makeSesi($siswa);

        $this->actingAs($pengajar)
            ->patch("/dashboard/sesi-belajar/{$sesi->id}/catatan", [
                'catatan' => 'Akses ditolak',
            ])
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
