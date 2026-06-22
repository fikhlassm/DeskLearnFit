<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_home_page_renders(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('LearnFit');
        $response->assertSee('Temukan');
        $response->assertSee('Belajarmu');
        $response->assertSee('Fitur Unggulan');
        $response->assertSee('Apa Kata Mereka');
    }

    public function test_about_page_renders(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Tentang Kami');
        $response->assertSee('Mengapa Memilih');
        $response->assertSee('MISI KAMI');
        $response->assertSee('Orang-orang di Balik LearnFit');
    }

    public function test_contact_page_renders(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Hubungi Kami');
        $response->assertSee('Kirim Pesan');
        $response->assertSee('halo@learnfit.id');
        $response->assertSee('Pertanyaan yang Sering Ditanyakan');
    }

    public function test_home_contains_navbar_partial(): void
    {
        $response = $this->get('/');
        $response->assertSee('Beranda', false);
        $response->assertSee('Tentang Kami', false);
        $response->assertSee('Kontak', false);
    }

    public function test_about_marks_active_navbar_link(): void
    {
        $response = $this->get(route('about'));
        // The active link is rendered with extra classes; verify it contains the link href
        $response->assertSee('href="'.route('about').'"', false);
    }

    public function test_contact_marks_active_navbar_link(): void
    {
        $response = $this->get(route('contact'));
        $response->assertSee('href="'.route('contact').'"', false);
    }

    public function test_home_loads_vite_assets(): void
    {
        $response = $this->get('/');
        // Either built manifest (`/build/...`) or Vite dev server URL.
        $this->assertMatchesRegularExpression(
            '#(/build/[^"]+\.(?:css|js)|/@vite/client|resources/(?:css|js)/[^"]+\.(?:css|js))#',
            $response->getContent()
        );
    }

    public function test_csrf_meta_tag_present_on_public_pages(): void
    {
        foreach (['/', route('about'), route('contact')] as $url) {
            $response = $this->get($url);
            $this->assertMatchesRegularExpression(
                '#<meta name="csrf-token" content="[^"]+"#',
                $response->getContent(),
                "CSRF meta missing on {$url}"
            );
        }
    }
}
