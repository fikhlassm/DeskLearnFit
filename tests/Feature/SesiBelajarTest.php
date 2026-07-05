<?php

namespace Tests\Feature;

use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SesiBelajarTest extends TestCase
{
    use RefreshDatabase;

    private function validSesi(array $overrides = []): array
    {
        return array_merge([
            'metode' => 'pomodoro',
            'judul' => 'Belajar Kalkulus',
            'durasi_fokus_menit' => 25,
            'durasi_istirahat_menit' => 5,
            'jumlah_siklus' => 4,
        ], $overrides);
    }

    public function test_siswa_bisa_lihat_halaman_sesi(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/sesi-belajar')
            ->assertStatus(200);
    }

    public function test_siswa_bisa_buat_sesi(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/sesi-belajar', $this->validSesi())
            ->assertRedirect(route('sesi.show', SesiBelajar::first()->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('sesi_belajar', [
            'user_id' => $siswa->id,
            'metode' => 'pomodoro',
            'judul' => 'Belajar Kalkulus',
        ]);
    }

    public function test_siswa_bisa_buat_sesi_active_recall_tanpa_timer(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/sesi-belajar', [
                'metode' => 'active_recall',
                'judul' => 'Kuis Kalkulus',
            ])
            ->assertRedirect(route('sesi.show', SesiBelajar::first()->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('sesi_belajar', [
            'user_id' => $siswa->id,
            'metode' => 'active_recall',
            'judul' => 'Kuis Kalkulus',
        ]);
    }

    public function test_metode_wajib_valid(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/sesi-belajar', $this->validSesi(['metode' => 'metode_tidak_ada']))
            ->assertSessionHasErrors('metode');
    }

    public function test_durasi_fokus_wajib_diisi(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/sesi-belajar', array_merge($this->validSesi(), ['durasi_fokus_menit' => '']))
            ->assertSessionHasErrors('durasi_fokus_menit');
    }

    public function test_siswa_bisa_complete_sesi_miliknya(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = SesiBelajar::factory()->create([
            'user_id' => $siswa->id,
            'status' => 'aktif',
            'started_at' => now(),
        ]);

        $this->actingAs($siswa)
            ->patch('/dashboard/sesi-belajar/'.$sesi->id.'/complete')
            ->assertRedirect(route('sesi.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('sesi_belajar', [
            'id' => $sesi->id,
            'status' => 'selesai',
        ]);
    }

    public function test_siswa_tidak_bisa_complete_sesi_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = SesiBelajar::factory()->create([
            'user_id' => $lain->id,
            'status' => 'aktif',
        ]);

        $this->actingAs($siswa)
            ->patch('/dashboard/sesi-belajar/'.$sesi->id.'/complete')
            ->assertForbidden();
    }

    public function test_siswa_bisa_hapus_sesi_miliknya(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = SesiBelajar::factory()->create([
            'user_id' => $siswa->id,
            'metode' => 'pomodoro',
        ]);

        $this->actingAs($siswa)
            ->delete('/dashboard/sesi-belajar/'.$sesi->id)
            ->assertRedirect(route('sesi.index', ['metode' => 'pomodoro']));

        $this->assertDatabaseMissing('sesi_belajar', ['id' => $sesi->id]);
    }

    public function test_siswa_tidak_bisa_hapus_sesi_user_lain(): void
    {
        $siswa = User::factory()->siswa()->create();
        $lain = User::factory()->siswa()->create();
        $sesi = SesiBelajar::factory()->create(['user_id' => $lain->id]);

        $this->actingAs($siswa)
            ->delete('/dashboard/sesi-belajar/'.$sesi->id)
            ->assertForbidden();
    }

    public function test_pengajar_tidak_bisa_akses_sesi_belajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/sesi-belajar')
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
