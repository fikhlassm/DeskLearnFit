<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MateriDownloadTest extends TestCase
{
    use RefreshDatabase;

    private function makePengajar(): User
    {
        return User::factory()->pengajar()->create();
    }

    private function makeSiswa(User $pengajar): User
    {
        $siswa = User::factory()->siswa()->create();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $kelas->siswa()->attach($siswa->id, ['joined_at' => now()]);

        return $siswa;
    }

    public function test_pengajar_bisa_download_file_materi_sendiri(): void
    {
        Storage::fake('public');
        $pengajar = $this->makePengajar();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $file = UploadedFile::fake()->create('materi.pdf', 100);
        $path = $file->store('materi', 'public');
        $materi = Materi::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'tipe' => 'file',
            'file_path' => $path,
            'status' => 'terbit',
        ]);

        $this->actingAs($pengajar)
            ->get("/dashboard/materi/{$materi->id}/download")
            ->assertDownload();
    }

    public function test_siswa_yang_join_kelas_bisa_download(): void
    {
        Storage::fake('public');
        $pengajar = $this->makePengajar();
        $siswa = $this->makeSiswa($pengajar);
        $kelas = $pengajar->kelasDiajar()->first();
        $path = UploadedFile::fake()->create('slide.pptx', 200)->store('materi', 'public');
        $materi = Materi::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'tipe' => 'file',
            'file_path' => $path,
            'status' => 'terbit',
        ]);

        $this->actingAs($siswa)
            ->get("/dashboard/materi/{$materi->id}/download")
            ->assertDownload();
    }

    public function test_siswa_yang_tidak_join_kelas_tidak_bisa_download(): void
    {
        Storage::fake('public');
        $pengajar = $this->makePengajar();
        $stranger = User::factory()->siswa()->create();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $path = UploadedFile::fake()->create('materi.pdf', 100)->store('materi', 'public');
        $materi = Materi::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'tipe' => 'file',
            'file_path' => $path,
            'status' => 'terbit',
        ]);

        $this->actingAs($stranger)
            ->get("/dashboard/materi/{$materi->id}/download")
            ->assertForbidden();
    }

    public function test_siswa_tidak_bisa_download_materi_yang_belum_terbit(): void
    {
        Storage::fake('public');
        $pengajar = $this->makePengajar();
        $siswa = $this->makeSiswa($pengajar);
        $kelas = $pengajar->kelasDiajar()->first();
        $path = UploadedFile::fake()->create('draft.pdf', 100)->store('materi', 'public');
        $materi = Materi::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'tipe' => 'file',
            'file_path' => $path,
            'status' => 'draf',
        ]);

        $this->actingAs($siswa)
            ->get("/dashboard/materi/{$materi->id}/download")
            ->assertNotFound();
    }

    public function test_download_file_yang_tidak_ada_di_storage_akan_404(): void
    {
        Storage::fake('public');
        $pengajar = $this->makePengajar();
        $kelas = Kelas::factory()->create(['pengajar_id' => $pengajar->id]);
        $materi = Materi::factory()->create([
            'kelas_id' => $kelas->id,
            'pengajar_id' => $pengajar->id,
            'tipe' => 'file',
            'file_path' => 'materi/nonexistent.pdf',
            'status' => 'terbit',
        ]);

        $this->actingAs($pengajar)
            ->get("/dashboard/materi/{$materi->id}/download")
            ->assertNotFound();
    }

    public function test_guest_tidak_bisa_download_materi(): void
    {
        $this->get('/dashboard/materi/1/download')
            ->assertRedirect(route('login'));
    }
}
