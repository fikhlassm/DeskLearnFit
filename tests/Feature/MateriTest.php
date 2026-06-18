<?php

namespace Tests\Feature;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MateriTest extends TestCase
{
    use RefreshDatabase;

    /** Buat environment: pengajar, kelas miliknya, siswa terdaftar. */
    private function setupKelasWithSiswa(): array
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas    = Kelas::factory()->milikPengajar($pengajar->id)->aktif()->create();
        $siswa    = User::factory()->siswa()->create();

        AnggotaKelas::create([
            'kelas_id'  => $kelas->id,
            'siswa_id'  => $siswa->id,
            'joined_at' => now(),
        ]);

        return [$pengajar, $kelas, $siswa];
    }

    // ── Pengajar CRUD ─────────────────────────────────────────────────────────

    public function test_pengajar_bisa_membuat_materi_di_kelas_miliknya(): void
    {
        [$pengajar, $kelas] = $this->setupKelasWithSiswa();

        $this->actingAs($pengajar)
            ->post("/dashboard/kelas/{$kelas->id}/materi", [
                'judul'  => 'Pengenalan Kalkulus',
                'konten' => 'Kalkulus adalah cabang matematika...',
                'tipe'   => 'teks',
            ])
            ->assertRedirect(route('materi.index', $kelas));

        $this->assertDatabaseHas('materi', [
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'judul'       => 'Pengenalan Kalkulus',
            'status'      => 'draf',
        ]);
    }

    public function test_pengajar_tidak_bisa_membuat_materi_di_kelas_orang_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();
        $kelas     = Kelas::factory()->milikPengajar($pengajarB->id)->create();

        $this->actingAs($pengajarA)
            ->post("/dashboard/kelas/{$kelas->id}/materi", [
                'judul' => 'Materi Tidak Sah',
                'tipe'  => 'teks',
            ])
            ->assertForbidden();
    }

    public function test_pengajar_bisa_update_materi_miliknya(): void
    {
        [$pengajar, $kelas] = $this->setupKelasWithSiswa();
        $materi = Materi::factory()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($pengajar)
            ->put("/dashboard/materi/{$materi->id}", [
                'judul'  => 'Judul Diperbarui',
                'konten' => 'Konten baru',
                'tipe'   => 'teks',
            ])
            ->assertRedirect(route('materi.index', $kelas->id));

        $this->assertDatabaseHas('materi', [
            'id'    => $materi->id,
            'judul' => 'Judul Diperbarui',
        ]);
    }

    public function test_pengajar_bisa_hapus_materi_miliknya(): void
    {
        [$pengajar, $kelas] = $this->setupKelasWithSiswa();
        $materi = Materi::factory()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($pengajar)
            ->delete("/dashboard/materi/{$materi->id}")
            ->assertRedirect(route('materi.index', $kelas->id));

        $this->assertDatabaseMissing('materi', ['id' => $materi->id]);
    }

    public function test_pengajar_bisa_publish_materi(): void
    {
        [$pengajar, $kelas] = $this->setupKelasWithSiswa();
        $materi = Materi::factory()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'status'      => 'draf',
        ]);

        $this->actingAs($pengajar)
            ->patch("/dashboard/materi/{$materi->id}/publish")
            ->assertRedirect(route('materi.index', $kelas->id));

        $this->assertDatabaseHas('materi', [
            'id'     => $materi->id,
            'status' => 'terbit',
        ]);
    }

    // ── Siswa ─────────────────────────────────────────────────────────────────

    public function test_siswa_bisa_melihat_materi_terbit_dari_kelas_yang_diikuti(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupKelasWithSiswa();
        $materi = Materi::factory()->terbit()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'judul'       => 'Materi Terbit',
        ]);

        $this->actingAs($siswa)
            ->get("/dashboard/siswa/kelas/{$kelas->id}/materi")
            ->assertStatus(200)
            ->assertSee('Materi Terbit');
    }

    public function test_siswa_tidak_bisa_melihat_materi_draf(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupKelasWithSiswa();
        Materi::factory()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'status'      => 'draf',
            'judul'       => 'Materi Rahasia Draf',
        ]);

        $this->actingAs($siswa)
            ->get("/dashboard/siswa/kelas/{$kelas->id}/materi")
            ->assertStatus(200)
            ->assertDontSee('Materi Rahasia Draf');
    }

    public function test_siswa_tidak_bisa_melihat_materi_dari_kelas_yang_tidak_diikuti(): void
    {
        $pengajar  = User::factory()->pengajar()->create();
        $kelas     = Kelas::factory()->milikPengajar($pengajar->id)->create();
        $siswaLuar = User::factory()->siswa()->create();
        $materi    = Materi::factory()->terbit()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswaLuar)
            ->get("/dashboard/siswa/materi/{$materi->id}")
            ->assertForbidden();
    }

    public function test_siswa_tidak_bisa_hapus_materi(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupKelasWithSiswa();
        $materi = Materi::factory()->terbit()->create([
            'kelas_id'    => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswa)
            ->delete("/dashboard/materi/{$materi->id}")
            ->assertRedirect(route('dashboard.siswa'));
    }
}
