<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthFeaturesTest extends TestCase
{
    use RefreshDatabase;

    // ── Forgot / Reset Password ──────────────────────────────────────────────

    public function test_guest_bisa_lihat_form_lupa_password(): void
    {
        $this->get('/lupa-password')->assertStatus(200);
    }

    public function test_email_tidak_terdaftar_menolak_reset(): void
    {
        $this->post('/lupa-password', ['email' => 'takada@test.com'])
            ->assertSessionHasErrors('email');
    }

    public function test_email_terdaftar_akan_insert_token_ke_tabel(): void
    {
        $user = User::factory()->siswa()->create(['email' => 'ada@test.com']);

        $this->post('/lupa-password', ['email' => 'ada@test.com'])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'ada@test.com',
        ]);
    }

    public function test_guest_bisa_lihat_form_reset_dengan_token(): void
    {
        $this->get('/reset-password/dummytoken?email=foo@bar.com')
            ->assertStatus(200);
    }

    public function test_reset_dengan_token_salah_ditolak(): void
    {
        $user = User::factory()->siswa()->create(['email' => 'user@test.com']);
        \DB::table('password_reset_tokens')->insert([
            'email' => 'user@test.com',
            'token' => Hash::make('benar-token'),
            'created_at' => now(),
        ]);

        $this->post('/reset-password', [
            'email' => 'user@test.com',
            'token' => 'salah-token',
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ])->assertSessionHasErrors('email');

        $user->refresh();
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_reset_dengan_token_benar_berhasil_dan_login(): void
    {
        $user = User::factory()->siswa()->create(['email' => 'user@test.com']);
        \DB::table('password_reset_tokens')->insert([
            'email' => 'user@test.com',
            'token' => Hash::make('benar-token'),
            'created_at' => now(),
        ]);

        $response = $this->post('/reset-password', [
            'email' => 'user@test.com',
            'token' => 'benar-token',
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertRedirect(route('dashboard.siswa'));

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'user@test.com']);

        $user->refresh();
        $this->assertTrue(Hash::check('password-baru-123', $user->password));
    }

    // ── Email Verification ───────────────────────────────────────────────────

    public function test_user_dengan_email_belum_verifikasi_masih_bisa_akses_dashboard(): void
    {
        $user = User::factory()->siswa()->create(['email_verified_at' => null]);

        $this->actingAs($user)->get('/dashboard/siswa')->assertStatus(200);

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_user_yang_sudah_verifikasi_bisa_akses_dashboard(): void
    {
        $user = User::factory()->siswa()->create(['email_verified_at' => now()]);

        $this->actingAs($user)->get('/dashboard/siswa')->assertStatus(200);
    }

    public function test_verify_email_dengan_link_valid_berhasil(): void
    {
        $user = User::factory()->siswa()->create(['email_verified_at' => null]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->actingAs($user)->get($url)
            ->assertRedirect(route('dashboard.siswa'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_resend_verification_menampilkan_link_di_dev(): void
    {
        $user = User::factory()->siswa()->create(['email_verified_at' => null]);

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertSessionHas('success');
    }

    // ── Google Sign-In ───────────────────────────────────────────────────────

    public function test_google_redirect_tanpa_konfigurasi_akan_redirect_ke_login_dengan_error(): void
    {
        config(['services.google.client_id' => null, 'services.google.client_secret' => null]);

        $this->get('/auth/google')
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');
    }

    public function test_login_view_menampilkan_tombol_google(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Masuk dengan Google');
    }
}
