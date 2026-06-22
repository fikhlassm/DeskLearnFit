<?php

namespace Database\Factories;

use App\Models\EntriNotebook;
use App\Models\SesiBelajar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EntriNotebook>
 */
class EntriNotebookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sesi_id' => SesiBelajar::factory(),
            'tipe' => fake()->randomElement([EntriNotebook::TIPE_BLURTING, EntriNotebook::TIPE_FEYNMAN]),
            'konten' => fake()->paragraph(4),
            'analisis_sistem' => 'Heuristik: '.fake()->sentence(10),
            'skor_keyakinan' => fake()->numberBetween(0, 100),
            'kata_kunci_cocok' => ['kata1', 'kata2'],
        ];
    }

    public function blurting(): static
    {
        return $this->state(['tipe' => EntriNotebook::TIPE_BLURTING]);
    }

    public function feynman(): static
    {
        return $this->state(['tipe' => EntriNotebook::TIPE_FEYNMAN]);
    }
}
