<?php

namespace Tests\Feature;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaKelasTest extends TestCase
{
    use RefreshDatabase;

    // ── Join ──────────────────────────────────────────────────────────────────

    public function test_siswa_bisa_join_kelas_aktif_dengan_kode_valid(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->aktif()->create(['kode_kelas' => 'AKTIF01']);
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/kelas-diikuti/join', ['kode_kelas' => 'AKTIF01'])
            ->assertRedirect(route('siswa.kelas.index'));

        $this->assertDatabaseHas('anggota_kelas', [
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
        ]);
    }

    public function test_siswa_tidak_bisa_join_kode_salah(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/kelas-diikuti/join', ['kode_kelas' => 'TIDAKADA'])
            ->assertSessionHasErrors('kode_kelas');
    }

    public function test_siswa_tidak_bisa_join_kelas_tidak_aktif(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        Kelas::factory()->milikPengajar($pengajar->id)->draf()->create(['kode_kelas' => 'DRAF01']);
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->post('/dashboard/kelas-diikuti/join', ['kode_kelas' => 'DRAF01'])
            ->assertSessionHasErrors('kode_kelas');
    }

    public function test_siswa_tidak_bisa_join_kelas_yang_sama_dua_kali(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->aktif()->create(['kode_kelas' => 'DUP01']);
        $siswa = User::factory()->siswa()->create();

        AnggotaKelas::create([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'joined_at' => now(),
        ]);

        $this->actingAs($siswa)
            ->post('/dashboard/kelas-diikuti/join', ['kode_kelas' => 'DUP01'])
            ->assertSessionHasErrors('kode_kelas');
    }

    // ── Leave ─────────────────────────────────────────────────────────────────

    public function test_siswa_bisa_keluar_dari_kelas(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->create();
        $siswa = User::factory()->siswa()->create();

        AnggotaKelas::create([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'joined_at' => now(),
        ]);

        $this->actingAs($siswa)
            ->delete('/dashboard/kelas-diikuti/'.$kelas->id.'/leave')
            ->assertRedirect(route('siswa.kelas.index'));

        $this->assertDatabaseMissing('anggota_kelas', [
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
        ]);
    }

    // ── Peserta ───────────────────────────────────────────────────────────────

    public function test_pengajar_bisa_melihat_peserta_kelas_miliknya(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->create();
        $siswa = User::factory()->siswa()->create();

        AnggotaKelas::create([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'joined_at' => now(),
        ]);

        $this->actingAs($pengajar)
            ->get('/dashboard/kelas/'.$kelas->id.'/peserta')
            ->assertStatus(200)
            ->assertSee($siswa->name);
    }

    public function test_pengajar_tidak_bisa_melihat_peserta_kelas_pengajar_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajarB->id)->create();

        $this->actingAs($pengajarA)
            ->get('/dashboard/kelas/'.$kelas->id.'/peserta')
            ->assertForbidden();
    }

    // ── Guard ─────────────────────────────────────────────────────────────────

    public function test_guest_tidak_bisa_join_kelas(): void
    {
        $this->post('/dashboard/kelas-diikuti/join', ['kode_kelas' => 'XXX'])
            ->assertRedirect(route('login'));
    }

    public function test_pengajar_tidak_bisa_join_kelas_sebagai_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->post('/dashboard/kelas-diikuti/join', ['kode_kelas' => 'XXX'])
            ->assertRedirect(route('dashboard.pengajar'));
    }
}
