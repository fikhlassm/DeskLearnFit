<?php

namespace Tests\Feature;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengajar_lihat_daftar_siswa_yang_join_kelasnya(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $siswa1 = User::factory()->siswa()->create(['name' => 'Andi']);
        $siswa2 = User::factory()->siswa()->create(['name' => 'Budi']);
        $stranger = User::factory()->siswa()->create(['name' => 'Cici']);

        AnggotaKelas::factory()->create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa1->id]);
        AnggotaKelas::factory()->create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa2->id]);

        $this->actingAs($pengajar)
            ->get('/dashboard/daftar-siswa')
            ->assertStatus(200)
            ->assertViewIs('dashboard.pengajar.siswa-index')
            ->assertSee('Andi')
            ->assertSee('Budi')
            ->assertDontSee('Cici');
    }

    public function test_pengajar_tidak_lihat_siswa_dari_kelas_pengajar_lain(): void
    {
        $pengajar1 = User::factory()->pengajar()->create();
        $pengajar2 = User::factory()->pengajar()->create();
        $kelas1 = Kelas::factory()->create(['pengajar_id' => $pengajar1->id]);
        $kelas2 = Kelas::factory()->create(['pengajar_id' => $pengajar2->id]);
        $siswa = User::factory()->siswa()->create();

        AnggotaKelas::factory()->create(['kelas_id' => $kelas2->id, 'siswa_id' => $siswa->id]);

        $this->actingAs($pengajar1)
            ->get('/dashboard/daftar-siswa')
            ->assertStatus(200)
            ->assertDontSee($siswa->name);
    }

    public function test_siswa_tidak_bisa_akses_daftar_siswa(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/daftar-siswa')
            ->assertRedirect(route('dashboard.siswa'));
    }

    public function test_pencarian_siswa_berdasarkan_nama_dan_email(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $siswa = User::factory()->siswa()->create(['name' => 'Budi Sudarsono', 'email' => 'budi@test.com']);
        $lain = User::factory()->siswa()->create(['name' => 'Andi Wijaya', 'email' => 'andi@test.com']);

        AnggotaKelas::factory()->create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa->id]);
        AnggotaKelas::factory()->create(['kelas_id' => $kelas->id, 'siswa_id' => $lain->id]);

        $this->actingAs($pengajar)
            ->get('/dashboard/daftar-siswa?search=Budi')
            ->assertStatus(200)
            ->assertSee('Budi')
            ->assertDontSee('Andi');
    }

    public function test_pengajar_lihat_profil_detail_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $siswa = User::factory()->siswa()->create(['name' => 'Andi', 'quiz_result' => 'feynman']);
        AnggotaKelas::factory()->create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa->id]);
        SesiBelajar::factory()->count(3)->create(['user_id' => $siswa->id, 'status' => 'selesai']);

        $this->actingAs($pengajar)
            ->get("/dashboard/daftar-siswa/{$siswa->id}")
            ->assertStatus(200)
            ->assertViewIs('dashboard.pengajar.siswa-show')
            ->assertSee('Andi')
            ->assertSee('Feynman')
            ->assertSee('3');
    }

    public function test_pengajar_tidak_bisa_lihat_siswa_bukan_dari_kelasnya(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($pengajar)
            ->get("/dashboard/daftar-siswa/{$siswa->id}")
            ->assertForbidden();
    }

    public function test_pengajar_tidak_bisa_lihat_user_pengajar(): void
    {
        $pengajar1 = User::factory()->pengajar()->create();
        $pengajar2 = User::factory()->pengajar()->create();

        $this->actingAs($pengajar1)
            ->get("/dashboard/daftar-siswa/{$pengajar2->id}")
            ->assertNotFound();
    }

    public function test_profil_menampilkan_distribusi_metode_belajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $siswa = User::factory()->siswa()->create();
        AnggotaKelas::factory()->create(['kelas_id' => $kelas->id, 'siswa_id' => $siswa->id]);

        SesiBelajar::factory()->count(2)->create(['user_id' => $siswa->id, 'metode' => 'pomodoro', 'status' => 'selesai']);
        SesiBelajar::factory()->count(1)->create(['user_id' => $siswa->id, 'metode' => 'blurting', 'status' => 'selesai']);

        $this->actingAs($pengajar)
            ->get("/dashboard/daftar-siswa/{$siswa->id}")
            ->assertStatus(200)
            ->assertSee('Pomodoro')
            ->assertSee('Blurting')
            ->assertSee('2 sesi selesai')
            ->assertSee('1 sesi selesai');
    }
}
