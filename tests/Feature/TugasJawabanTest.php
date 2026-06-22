<?php

namespace Tests\Feature;

use App\Models\AnggotaKelas;
use App\Models\JawabanTugas;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TugasJawabanTest extends TestCase
{
    use RefreshDatabase;

    private function setupEnv(): array
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->aktif()->create();
        $siswa = User::factory()->siswa()->create();

        AnggotaKelas::create([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'joined_at' => now(),
        ]);

        return [$pengajar, $kelas, $siswa];
    }

    // ── Pengajar: buat & publish tugas ────────────────────────────────────────

    public function test_pengajar_bisa_membuat_tugas_di_kelas_miliknya(): void
    {
        [$pengajar, $kelas] = $this->setupEnv();

        $this->actingAs($pengajar)
            ->post("/dashboard/kelas/{$kelas->id}/tugas", [
                'judul' => 'Tugas Pertama',
                'deskripsi' => 'Kerjakan soal berikut ini.',
            ])
            ->assertRedirect(route('tugas.index', $kelas));

        $this->assertDatabaseHas('tugas', [
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'judul' => 'Tugas Pertama',
            'status' => 'draf',
        ]);
    }

    public function test_pengajar_tidak_bisa_membuat_tugas_di_kelas_orang_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajarB->id)->create();

        $this->actingAs($pengajarA)
            ->post("/dashboard/kelas/{$kelas->id}/tugas", [
                'judul' => 'Tidak Sah',
                'deskripsi' => 'test',
            ])
            ->assertForbidden();
    }

    public function test_pengajar_bisa_publish_tugas(): void
    {
        [$pengajar, $kelas] = $this->setupEnv();
        $tugas = Tugas::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'status' => 'draf',
        ]);

        $this->actingAs($pengajar)
            ->patch("/dashboard/tugas/{$tugas->id}/publish")
            ->assertRedirect(route('tugas.index', $kelas->id));

        $this->assertDatabaseHas('tugas', ['id' => $tugas->id, 'status' => 'terbit']);
    }

    // ── Siswa: lihat tugas ────────────────────────────────────────────────────

    public function test_siswa_bisa_melihat_tugas_terbit_dari_kelas_yang_diikuti(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'judul' => 'Tugas Aktif',
        ]);

        $this->actingAs($siswa)
            ->get("/dashboard/siswa/kelas/{$kelas->id}/tugas")
            ->assertStatus(200)
            ->assertSee('Tugas Aktif');
    }

    public function test_siswa_tidak_bisa_melihat_tugas_draf(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        Tugas::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'status' => 'draf',
            'judul' => 'Tugas Tersembunyi',
        ]);

        $this->actingAs($siswa)
            ->get("/dashboard/siswa/kelas/{$kelas->id}/tugas")
            ->assertDontSee('Tugas Tersembunyi');
    }

    public function test_siswa_tidak_bisa_melihat_tugas_dari_kelas_yang_tidak_diikuti(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->create();
        $siswaLuar = User::factory()->siswa()->create();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswaLuar)
            ->get("/dashboard/siswa/tugas/{$tugas->id}")
            ->assertForbidden();
    }

    // ── Siswa: submit jawaban ─────────────────────────────────────────────────

    public function test_siswa_bisa_submit_jawaban_tugas(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswa)
            ->post("/dashboard/siswa/tugas/{$tugas->id}/jawaban", [
                'jawaban_text' => 'Ini jawaban saya yang lengkap.',
            ])
            ->assertRedirect(route('siswa.tugas.show', $tugas));

        $this->assertDatabaseHas('jawaban_tugas', [
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'status' => 'terkirim',
        ]);
    }

    public function test_siswa_tidak_bisa_submit_dua_kali(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        JawabanTugas::create([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'jawaban_text' => 'Jawaban pertama',
            'submitted_at' => now(),
            'status' => 'terkirim',
        ]);

        $this->actingAs($siswa)
            ->post("/dashboard/siswa/tugas/{$tugas->id}/jawaban", [
                'jawaban_text' => 'Jawaban kedua',
            ])
            ->assertSessionHas('error');
    }

    public function test_siswa_tidak_bisa_submit_tugas_ditutup(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->ditutup()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswa)
            ->post("/dashboard/siswa/tugas/{$tugas->id}/jawaban", [
                'jawaban_text' => 'Coba submit setelah tutup',
            ])
            ->assertSessionHas('error');
    }

    public function test_siswa_tidak_bisa_submit_tugas_dari_kelas_yang_tidak_diikuti(): void
    {
        $pengajar = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajar->id)->create();
        $siswaLuar = User::factory()->siswa()->create();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswaLuar)
            ->post("/dashboard/siswa/tugas/{$tugas->id}/jawaban", [
                'jawaban_text' => 'Coba',
            ])
            ->assertForbidden();
    }

    // ── Pengajar: nilai jawaban ───────────────────────────────────────────────

    public function test_pengajar_bisa_menilai_jawaban_dari_tugas_miliknya(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);
        $jawaban = JawabanTugas::create([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'jawaban_text' => 'Jawaban',
            'submitted_at' => now(),
            'status' => 'terkirim',
        ]);

        $this->actingAs($pengajar)
            ->put("/dashboard/jawaban-tugas/{$jawaban->id}/nilai", [
                'nilai' => 85,
                'feedback' => 'Bagus sekali!',
            ])
            ->assertRedirect(route('tugas.jawaban.index', $tugas->id));

        $this->assertDatabaseHas('jawaban_tugas', [
            'id' => $jawaban->id,
            'nilai' => 85,
            'status' => 'dinilai',
        ]);
    }

    public function test_pengajar_tidak_bisa_menilai_jawaban_tugas_pengajar_lain(): void
    {
        $pengajarA = User::factory()->pengajar()->create();
        $pengajarB = User::factory()->pengajar()->create();
        $kelas = Kelas::factory()->milikPengajar($pengajarB->id)->create();
        $siswa = User::factory()->siswa()->create();

        AnggotaKelas::create([
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'joined_at' => now(),
        ]);

        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajarB->id,
        ]);
        $jawaban = JawabanTugas::create([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'jawaban_text' => 'Jawaban',
            'submitted_at' => now(),
            'status' => 'terkirim',
        ]);

        $this->actingAs($pengajarA)
            ->put("/dashboard/jawaban-tugas/{$jawaban->id}/nilai", ['nilai' => 50])
            ->assertForbidden();
    }

    public function test_nilai_harus_0_sampai_100(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->terbit()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);
        $jawaban = JawabanTugas::create([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'jawaban_text' => 'Jawaban',
            'submitted_at' => now(),
            'status' => 'terkirim',
        ]);

        $this->actingAs($pengajar)
            ->put("/dashboard/jawaban-tugas/{$jawaban->id}/nilai", ['nilai' => 150])
            ->assertSessionHasErrors('nilai');
    }

    public function test_tugas_ditutup_tidak_bisa_disubmit(): void
    {
        [$pengajar, $kelas, $siswa] = $this->setupEnv();
        $tugas = Tugas::factory()->ditutup()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
        ]);

        $this->actingAs($siswa)
            ->post("/dashboard/siswa/tugas/{$tugas->id}/jawaban", [
                'jawaban_text' => 'Terlambat',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('jawaban_tugas', [
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
        ]);
    }
}
