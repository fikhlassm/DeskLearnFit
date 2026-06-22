<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    // ── Guest ─────────────────────────────────────────────────────────────────

    public function test_guest_tidak_bisa_akses_dashboard_siswa(): void
    {
        $this->get('/dashboard/siswa')->assertRedirect(route('login'));
    }

    public function test_guest_tidak_bisa_akses_dashboard_pengajar(): void
    {
        $this->get('/dashboard/pengajar')->assertRedirect(route('login'));
    }

    public function test_guest_tidak_bisa_akses_quiz(): void
    {
        $this->get('/quiz')->assertRedirect(route('login'));
    }

    public function test_guest_tidak_bisa_akses_kelas(): void
    {
        $this->get('/dashboard/kelas')->assertRedirect(route('login'));
    }

    // ── Siswa akses terlarang ─────────────────────────────────────────────────

    public function test_siswa_tidak_bisa_akses_dashboard_pengajar(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/pengajar')
            ->assertRedirect(route('dashboard.siswa'));
    }

    public function test_siswa_tidak_bisa_akses_kelas_crud(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/kelas')
            ->assertRedirect(route('dashboard.siswa'));
    }

    public function test_siswa_tidak_bisa_store_kelas(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/kelas', [
                'nama_kelas' => 'Test Kelas',
                'mata_pelajaran' => 'Test',
                'kode_kelas' => 'TST-01',
                'kapasitas' => 30,
                'status' => 'aktif',
            ])
            ->assertRedirect(route('dashboard.siswa'));
    }

    // ── Pengajar akses terlarang ──────────────────────────────────────────────

    public function test_pengajar_tidak_bisa_akses_dashboard_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/siswa')
            ->assertRedirect(route('dashboard.pengajar'));
    }

    public function test_pengajar_tidak_bisa_akses_quiz(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/quiz')
            ->assertRedirect(route('dashboard.pengajar'));
    }

    public function test_pengajar_tidak_bisa_submit_quiz(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->post('/quiz', [])
            ->assertRedirect(route('dashboard.pengajar'));
    }

    public function test_pengajar_tidak_bisa_akses_catatan_belajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/catatan-belajar')
            ->assertRedirect(route('dashboard.pengajar'));
    }

    // ── Akses yang seharusnya berhasil ────────────────────────────────────────

    public function test_siswa_bisa_akses_dashboard_siswa(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/siswa')
            ->assertStatus(200);
    }

    public function test_pengajar_bisa_akses_dashboard_pengajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/pengajar')
            ->assertStatus(200);
    }

    public function test_siswa_bisa_akses_quiz_jika_belum_quiz(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/quiz')
            ->assertStatus(200);
    }

    public function test_profil_bisa_diakses_siswa(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/profil')
            ->assertStatus(200);
    }

    public function test_profil_bisa_diakses_pengajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/profil')
            ->assertStatus(200);
    }
}
