<?php

namespace Tests\Feature;

use App\Models\EntriNotebook;
use App\Models\Flashcard;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotebookIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_bisa_lihat_halaman_notebook(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/notebook')
            ->assertStatus(200)
            ->assertViewIs('dashboard.notebook-index')
            ->assertSee('Catatan Belajar')
            ->assertSee('Pomodoro')
            ->assertSee('Active Recall')
            ->assertSee('Blurting')
            ->assertSee('Feynman');
    }

    public function test_notebook_mengelompokkan_sesi_per_metode(): void
    {
        $siswa = User::factory()->siswa()->create();

        SesiBelajar::factory()->count(2)->create(['user_id' => $siswa->id, 'metode' => 'pomodoro']);
        SesiBelajar::factory()->count(1)->create(['user_id' => $siswa->id, 'metode' => 'feynman']);
        SesiBelajar::factory()->count(3)->create(['user_id' => $siswa->id, 'metode' => 'blurting']);

        $response = $this->actingAs($siswa)
            ->get('/dashboard/notebook');

        $response->assertSee('2', false)
            ->assertSee('sesi', false);
    }

    public function test_notebook_menampilkan_total_kartu_dan_review_untuk_active_recall(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = SesiBelajar::factory()->create([
            'user_id' => $siswa->id,
            'metode' => 'active_recall',
        ]);
        Flashcard::factory()->count(4)->create(['sesi_id' => $sesi->id]);

        $this->actingAs($siswa)
            ->get('/dashboard/notebook')
            ->assertSee('4 kartu');
    }

    public function test_notebook_menampilkan_total_entri_untuk_notebook(): void
    {
        $siswa = User::factory()->siswa()->create();
        $sesi = SesiBelajar::factory()->create([
            'user_id' => $siswa->id,
            'metode' => 'blurting',
        ]);
        EntriNotebook::factory()->count(2)->create(['sesi_id' => $sesi->id, 'tipe' => 'blurting']);

        $this->actingAs($siswa)
            ->get('/dashboard/notebook')
            ->assertSee('2 entri');
    }

    public function test_pengajar_tidak_bisa_akses_notebook_siswa(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/notebook')
            ->assertRedirect(route('dashboard.pengajar'));
    }

    public function test_siswa_lain_tidak_terlihat_di_notebook_user_ini(): void
    {
        $siswa1 = User::factory()->siswa()->create(['name' => 'Andi']);
        $siswa2 = User::factory()->siswa()->create(['name' => 'Budi']);

        SesiBelajar::factory()->create([
            'user_id' => $siswa2->id,
            'metode' => 'pomodoro',
            'judul' => 'Sesi Rahasia Budi',
        ]);

        $this->actingAs($siswa1)
            ->get('/dashboard/notebook')
            ->assertStatus(200)
            ->assertDontSee('Sesi Rahasia Budi');
    }
}
