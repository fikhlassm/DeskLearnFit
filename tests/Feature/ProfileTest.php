<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_bisa_lihat_profil(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->get('/dashboard/profil')
            ->assertStatus(200)
            ->assertViewHas('user', $siswa);
    }

    public function test_pengajar_bisa_lihat_profil(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $this->actingAs($pengajar)
            ->get('/dashboard/profil')
            ->assertStatus(200);
    }

    public function test_user_bisa_update_nama_dan_email(): void
    {
        $user = User::factory()->siswa()->create();

        $this->actingAs($user)
            ->put('/dashboard/profil', [
                'name' => 'Nama Baru',
                'email' => $user->email,
            ])
            ->assertRedirect(route('profil.show'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nama Baru']);
    }

    public function test_email_harus_unik_kecuali_milik_sendiri(): void
    {
        $user = User::factory()->siswa()->create();
        $other = User::factory()->siswa()->create(['email' => 'other@example.com']);

        $this->actingAs($user)
            ->put('/dashboard/profil', [
                'name' => $user->name,
                'email' => 'other@example.com',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_update_email_milik_sendiri_tidak_error(): void
    {
        $user = User::factory()->siswa()->create();

        $this->actingAs($user)
            ->put('/dashboard/profil', [
                'name' => $user->name,
                'email' => $user->email,
            ])
            ->assertRedirect(route('profil.show'))
            ->assertSessionHasNoErrors();
    }

    public function test_role_tidak_bisa_diubah_melalui_request(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->put('/dashboard/profil', [
                'name' => $siswa->name,
                'email' => $siswa->email,
                'role' => 'pengajar', // coba inject role
            ]);

        $siswa->refresh();
        $this->assertEquals('siswa', $siswa->role);
    }

    public function test_update_profil_siswa_dengan_field_tambahan(): void
    {
        $siswa = User::factory()->siswa()->create();

        $this->actingAs($siswa)
            ->put('/dashboard/profil', [
                'name' => $siswa->name,
                'email' => $siswa->email,
                'bio' => 'Siswa aktif yang suka belajar.',
                'tujuan_belajar' => 'Lulus SNBT 2025',
                'jenjang' => 'SMA',
                'no_hp' => '081234567890',
            ])
            ->assertRedirect(route('profil.show'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $siswa->id,
            'jenjang' => 'SMA',
        ]);
    }

    public function test_guest_tidak_bisa_akses_profil(): void
    {
        $this->get('/dashboard/profil')->assertRedirect(route('login'));
    }
}
