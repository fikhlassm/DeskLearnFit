<?php

namespace Tests\Feature;

use App\Models\JurnalBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JurnalBelajarTest extends TestCase
{
    use RefreshDatabase;

    private function validJurnal(array $overrides = []): array
    {
        return array_merge([
            'tanggal' => now()->format('Y-m-d'),
            'judul' => 'Belajar Turunan',
            'isi_jurnal' => 'Hari ini saya mempelajari konsep turunan fungsi.',
            'metode_yang_digunakan' => 'pomodoro',
            'rating_efektivitas' => 4,
            'durasi_menit' => 45,
        ], $overrides);
    }

    public function test_siswa_bisa_lihat_catatan_belajar(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/catatan-belajar')
            ->assertStatus(200);
    }

    public function test_siswa_bisa_buat_catatan_belajar(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/catatan-belajar', $this->validJurnal())
            ->assertRedirect(route('catatan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jurnal_belajar', [
            'user_id' => $siswa->id,
            'judul' => 'Belajar Turunan',
            'isi_jurnal' => 'Hari ini saya mempelajari konsep turunan fungsi.',
        ]);
    }

    public function test_catatan_wajib_diisi(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/catatan-belajar', ['tanggal' => now()->format('Y-m-d')])
            ->assertSessionHasErrors('isi_jurnal');
    }

    public function test_tanggal_tidak_boleh_masa_depan(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/catatan-belajar', $this->validJurnal([
                'tanggal' => now()->addDay()->format('Y-m-d'),
            ]))
            ->assertSessionHasErrors('tanggal');
    }

    public function test_siswa_bisa_update_catatan_miliknya(): void
    {
        $siswa = User::factory()->siswa()->create();
        $jurnal = JurnalBelajar::factory()->create(['user_id' => $siswa->id]);

        $this->actingAs($siswa)
            ->put('/dashboard/catatan-belajar/'.$jurnal->id, $this->validJurnal([
                'isi_jurnal' => 'Update: belajar lebih dalam tentang turunan.',
            ]))
            ->assertRedirect(route('catatan.index'));

        $this->assertDatabaseHas('jurnal_belajar', [
            'id' => $jurnal->id,
            'isi_jurnal' => 'Update: belajar lebih dalam tentang turunan.',
        ]);
    }

    public function test_siswa_tidak_bisa_update_catatan_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $jurnal = JurnalBelajar::factory()->create(['user_id' => $lain->id]);

        $this->actingAs($siswa)
            ->put('/dashboard/catatan-belajar/'.$jurnal->id, $this->validJurnal())
            ->assertForbidden();
    }

    public function test_siswa_bisa_hapus_catatan_miliknya(): void
    {
        $siswa = User::factory()->siswa()->create();
        $jurnal = JurnalBelajar::factory()->create(['user_id' => $siswa->id]);

        $this->actingAs($siswa)
            ->delete('/dashboard/catatan-belajar/'.$jurnal->id)
            ->assertRedirect(route('catatan.index'));

        $this->assertDatabaseMissing('jurnal_belajar', ['id' => $jurnal->id]);
    }

    public function test_siswa_tidak_bisa_hapus_catatan_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $jurnal = JurnalBelajar::factory()->create(['user_id' => $lain->id]);

        $this->actingAs($siswa)
            ->delete('/dashboard/catatan-belajar/'.$jurnal->id)
            ->assertForbidden();
    }

    public function test_pengajar_tidak_bisa_akses_catatan_belajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/catatan-belajar')
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
