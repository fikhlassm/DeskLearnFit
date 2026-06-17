<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasCrudTest extends TestCase
{
    use RefreshDatabase;

    private function kelasData(array $overrides = []): array
    {
        return array_merge([
            'nama_kelas'     => 'Matematika Dasar',
            'mata_pelajaran' => 'Matematika',
            'kode_kelas'     => 'MTK-TST-01',
            'deskripsi'      => 'Kelas test',
            'kapasitas'      => 30,
            'status'         => 'aktif',
        ], $overrides);
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function test_pengajar_bisa_buat_kelas(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->post('/dashboard/kelas', $this->kelasData())
            ->assertRedirect(route('dashboard.kelas'));

        $this->assertDatabaseHas('kelas', [
            'kode_kelas'  => 'MTK-TST-01',
            'pengajar_id' => $pengajar->id,
        ]);
    }

    public function test_store_otomatis_mengisi_pengajar_id_dari_user_login(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->post('/dashboard/kelas', $this->kelasData(['pengajar_id' => 99999]));

        $kelas = Kelas::where('kode_kelas', 'MTK-TST-01')->first();
        $this->assertEquals($pengajar->id, $kelas->pengajar_id);
    }

    public function test_siswa_tidak_bisa_buat_kelas(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/kelas', $this->kelasData())
            ->assertRedirect(route('dashboard.siswa'));

        $this->assertDatabaseMissing('kelas', ['kode_kelas' => 'MTK-TST-01']);
    }

    public function test_kode_kelas_harus_unik(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        Kelas::factory()->milikPengajar($pengajar->id)->create(['kode_kelas' => 'MTK-TST-01']);

        $this->actingAs($pengajar)
            ->post('/dashboard/kelas', $this->kelasData())
            ->assertSessionHasErrors('kode_kelas');
    }

    // ── Read ──────────────────────────────────────────────────────────────────

    public function test_pengajar_hanya_melihat_kelas_miliknya(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();

        Kelas::factory()->milikPengajar($pengajarA->id)->create(['nama_kelas' => 'Kelas A']);
        Kelas::factory()->milikPengajar($pengajarB->id)->create(['nama_kelas' => 'Kelas B']);

        $this->actingAs($pengajarA)
            ->get('/dashboard/kelas')
            ->assertStatus(200)
            ->assertSee('Kelas A')
            ->assertDontSee('Kelas B');
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function test_pengajar_bisa_update_kelas_miliknya(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas    = Kelas::factory()->milikPengajar($pengajar->id)->create(['kode_kelas' => 'MTK-TST-01']);

        $this->actingAs($pengajar)
            ->put('/dashboard/kelas/' . $kelas->id, $this->kelasData([
                'nama_kelas' => 'Matematika Lanjutan',
                'kode_kelas' => 'MTK-TST-01',
            ]))
            ->assertRedirect(route('dashboard.kelas'));

        $this->assertDatabaseHas('kelas', ['id' => $kelas->id, 'nama_kelas' => 'Matematika Lanjutan']);
    }

    public function test_pengajar_tidak_bisa_update_kelas_pengajar_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();
        $kelas     = Kelas::factory()->milikPengajar($pengajarB->id)->create();

        $this->actingAs($pengajarA)
            ->put('/dashboard/kelas/' . $kelas->id, $this->kelasData())
            ->assertForbidden();
    }

    public function test_siswa_tidak_bisa_update_kelas(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();
        $kelas = Kelas::factory()->create();

        $this->actingAs($siswa)
            ->put('/dashboard/kelas/' . $kelas->id, $this->kelasData())
            ->assertRedirect(route('dashboard.siswa'));
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function test_pengajar_bisa_hapus_kelas_miliknya(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas    = Kelas::factory()->milikPengajar($pengajar->id)->create();

        $this->actingAs($pengajar)
            ->delete('/dashboard/kelas/' . $kelas->id)
            ->assertRedirect(route('dashboard.kelas'));

        $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
    }

    public function test_pengajar_tidak_bisa_hapus_kelas_pengajar_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();
        $kelas     = Kelas::factory()->milikPengajar($pengajarB->id)->create();

        $this->actingAs($pengajarA)
            ->delete('/dashboard/kelas/' . $kelas->id)
            ->assertForbidden();
    }

    public function test_siswa_tidak_bisa_hapus_kelas(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();
        $kelas = Kelas::factory()->create();

        $this->actingAs($siswa)
            ->delete('/dashboard/kelas/' . $kelas->id)
            ->assertRedirect(route('dashboard.siswa'));

        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);
    }
}
