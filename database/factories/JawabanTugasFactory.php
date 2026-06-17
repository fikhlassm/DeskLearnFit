<?php

namespace Database\Factories;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JawabanTugas>
 */
class JawabanTugasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tugas_id'     => Tugas::factory()->terbit(),
            'siswa_id'     => User::factory()->siswa(),
            'jawaban_text' => fake()->paragraphs(2, true),
            'submitted_at' => now(),
            'status'       => 'terkirim',
            'nilai'        => null,
            'feedback'     => null,
        ];
    }
}
