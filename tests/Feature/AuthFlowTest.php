<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    // ── Register ──────────────────────────────────────────────────────────────

    public function test_register_siswa_redirects_to_welcome(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Budi Siswa',
            'email'                 => 'budi@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'siswa',
            'terms'                 => '1',
        ]);

        $response->assertRedirect(route('welcome'));
        $this->assertDatabaseHas('users', ['email' => 'budi@example.com', 'role' => 'siswa']);
    }

    public function test_register_pengajar_redirects_to_dashboard_pengajar(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Ibu Pengajar',
            'email'                 => 'ibu@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'pengajar',
            'terms'                 => '1',
        ]);

        $response->assertRedirect(route('dashboard.pengajar'));
    }

    public function test_register_requires_valid_role(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Hacker',
            'email'                 => 'hack@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'admin',
            'terms'                 => '1',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_register_requires_terms_accepted(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'No Terms',
            'email'                 => 'noterms@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'siswa',
        ]);

        $response->assertSessionHasErrors('terms');
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function test_login_siswa_tanpa_quiz_redirects_to_welcome(): void
    {
        $siswa = User::factory()->siswa()->create();

        $response = $this->post('/login', [
            'email'    => $siswa->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('welcome'));
    }

    public function test_login_siswa_dengan_quiz_redirects_to_dashboard_siswa(): void
    {
        $siswa = User::factory()->siswaWithQuiz()->create();

        $response = $this->post('/login', [
            'email'    => $siswa->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard.siswa'));
    }

    public function test_login_pengajar_redirects_to_dashboard_pengajar(): void
    {
        $pengajar = User::factory()->pengajar()->create();

        $response = $this->post('/login', [
            'email'    => $pengajar->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard.pengajar'));
    }

    public function test_login_dengan_kredensial_salah_gagal(): void
    {
        $user = User::factory()->siswa()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function test_logout_redirect_ke_home(): void
    {
        $user = User::factory()->siswa()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
