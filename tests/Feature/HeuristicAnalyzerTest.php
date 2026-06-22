<?php

namespace Tests\Feature;

use App\Models\EntriNotebook;
use App\Services\HeuristicAnalyzer;
use Tests\TestCase;

class HeuristicAnalyzerTest extends TestCase
{
    private HeuristicAnalyzer $analyzer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->analyzer = new HeuristicAnalyzer;
    }

    public function test_stopwords_disaring_dari_kata_kunci(): void
    {
        $hasil = $this->analyzer->analyze('Belajar Turunan Fungsi di Sekolah', '', EntriNotebook::TIPE_BLURTING);

        $this->assertArrayHasKey('kata_kunci_cocok', $hasil);
        $this->assertNotContains('di', $hasil['kata_kunci_cocok']);
    }

    public function test_pencocokan_kata_kunci_exact(): void
    {
        $topik = 'Fotosintesis Tumbuhan';
        $konten = 'Fotosintesis adalah proses tumbuhan mengubah cahaya menjadi energi. Fotosintesis membutuhkan air.';

        $hasil = $this->analyzer->analyze($topik, $konten, EntriNotebook::TIPE_BLURTING);

        $this->assertContains('fotosintesis', $hasil['kata_kunci_cocok']);
        $this->assertContains('tumbuhan', $hasil['kata_kunci_cocok']);
        $this->assertGreaterThan(50, $hasil['skor']);
    }

    public function test_case_insensitive_pencocokan(): void
    {
        $topik = 'Pythagoras Segitiga';
        $konten = 'pythagoras SEGITIGA siku-siku';

        $hasil = $this->analyzer->analyze($topik, $konten, EntriNotebook::TIPE_BLURTING);

        $this->assertContains('pythagoras', $hasil['kata_kunci_cocok']);
        $this->assertContains('segitiga', $hasil['kata_kunci_cocok']);
    }

    public function test_marker_feynman_memberi_bonus_skor(): void
    {
        $topik = 'Hukum Newton';
        $konten = 'Hukum Newton artinya gaya sama dengan massa kali percepatan. Misalnya pada benda jatuh. Jadi intinya gaya memengaruhi gerak.';

        $hasilFeynman = $this->analyzer->analyze($topik, $konten, EntriNotebook::TIPE_FEYNMAN);
        $hasilBlurting = $this->analyzer->analyze($topik, $konten, EntriNotebook::TIPE_BLURTING);

        $this->assertGreaterThan($hasilBlurting['skor'], $hasilFeynman['skor']);
    }

    public function test_blurting_memberi_bonus_jika_semua_kata_kunci_muncul(): void
    {
        $topik = 'Sel Hewan';

        $kontenLengkap = 'Sel hewan adalah unit terkecil penyusun tubuh hewan. Sel hewan memiliki membran, inti sel, dan sitoplasma. Sel berbeda dengan sel tumbuhan.';
        $kontenSebagian = 'Sel adalah unit tubuh.';

        $lengkap = $this->analyzer->analyze($topik, $kontenLengkap, EntriNotebook::TIPE_BLURTING);
        $sebagian = $this->analyzer->analyze($topik, $kontenSebagian, EntriNotebook::TIPE_BLURTING);

        $this->assertGreaterThan($sebagian['skor'], $lengkap['skor']);
        $this->assertGreaterThanOrEqual(60, $lengkap['skor']);
    }

    public function test_skor_dibatasi_0_sampai_100(): void
    {
        $topik = 'Topik';
        $konten = str_repeat('topik karena misalnya jadi artinya ', 50);

        $hasil = $this->analyzer->analyze($topik, $konten, EntriNotebook::TIPE_FEYNMAN);

        $this->assertGreaterThanOrEqual(0, $hasil['skor']);
        $this->assertLessThanOrEqual(100, $hasil['skor']);
    }

    public function test_narasi_mengandung_label_tipe(): void
    {
        $hasil = $this->analyzer->analyze('Topik X', 'Isi kontol', EntriNotebook::TIPE_FEYNMAN);
        $this->assertStringContainsString('Feynman', $hasil['analisis']);

        $hasil2 = $this->analyzer->analyze('Topik Y', 'Isi konten', EntriNotebook::TIPE_BLURTING);
        $this->assertStringContainsString('Blurting', $hasil2['analisis']);
    }

    public function test_topik_tanpa_kata_kunci_tidak_error(): void
    {
        $hasil = $this->analyzer->analyze('', 'konten sembarang', EntriNotebook::TIPE_BLURTING);

        $this->assertIsArray($hasil);
        $this->assertArrayHasKey('analisis', $hasil);
        $this->assertArrayHasKey('skor', $hasil);
        $this->assertGreaterThanOrEqual(0, $hasil['skor']);
    }
}
