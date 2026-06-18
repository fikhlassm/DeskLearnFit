<?php

namespace Tests\Feature;

use App\Models\AnggotaKelas;
use App\Models\JurnalBelajar;
use App\Models\Kelas;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDataTest extends TestCase
{
    use RefreshDatabase;

    // ── Dashboard Siswa ───────────────────────────────────────────────────────

    public function test_dashboard_siswa_menampilkan_data_milik_siswa_login(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        JurnalBelajar::factory()->count(3)->create(['user_id' => $siswa->id]);
        SesiBelajar::factory()->count(2)->create(['user_id' => $siswa->id, 'status' => 'selesai']);

        $response = $this->actingAs($siswa)->get('/dashboard/siswa');

        $response->assertStatus(200);
        $response->assertViewHas('totalCatatan', 3);
        $response->assertViewHas('totalSesiSelesai', 2);
    }

    public function test_dashboard_siswa_tidak_menghitung_data_siswa_lain(): void
    {
        $siswaA = User::factory()->siswaWithQuiz()->create();
        $siswaB = User::factory()->siswa()->create();

        JurnalBelajar::factory()->count(5)->create(['user_id' => $siswaB->id]);

        $response = $this->actingAs($siswaA)->get('/dashboard/siswa');

        $response->assertStatus(200);
        $response->assertViewHas('totalCatatan', 0);
    }

    public function test_dashboard_siswa_menampilkan_kelas_yang_diikuti(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas    = Kelas::factory()->milikPengajar($pengajar->id)->create(['nama_kelas' => 'Fisika Lanjutan']);
        $siswa    = User::factory()->siswaWithQuiz()->create();

        AnggotaKelas::create([
            'kelas_id'  => $kelas->id,
            'siswa_id'  => $siswa->id,
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($siswa)->get('/dashboard/siswa');

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('kelasDiikuti'));
    }

    // ── Dashboard Pengajar ────────────────────────────────────────────────────

    public function test_dashboard_pengajar_hanya_menghitung_kelas_miliknya(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();

        Kelas::factory()->count(2)->milikPengajar($pengajarA->id)->create();
        Kelas::factory()->count(5)->milikPengajar($pengajarB->id)->create();

        $response = $this->actingAs($pengajarA)->get('/dashboard/pengajar');

        $response->assertStatus(200);
        $response->assertViewHas('totalKelas', 2);
    }

    public function test_dashboard_pengajar_tidak_menghitung_kelas_pengajar_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();

        Kelas::factory()->count(3)->milikPengajar($pengajarB->id)->create();

        $response = $this->actingAs($pengajarA)->get('/dashboard/pengajar');

        $response->assertViewHas('totalKelas', 0);
    }

    // ── Role & guest guard ────────────────────────────────────────────────────

    public function test_guest_redirect_login_dari_dashboard_siswa(): void
    {
        $this->get('/dashboard/siswa')->assertRedirect(route('login'));
    }

    public function test_guest_redirect_login_dari_dashboard_pengajar(): void
    {
        $this->get('/dashboard/pengajar')->assertRedirect(route('login'));
    }

    public function test_siswa_tidak_bisa_akses_dashboard_pengajar(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/pengajar')
            ->assertRedirect(route('dashboard.siswa'));
    }

    public function test_pengajar_tidak_bisa_akses_dashboard_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/siswa')
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
