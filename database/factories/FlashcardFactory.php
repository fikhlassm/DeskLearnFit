<?php

namespace Database\Factories;

use App\Models\Flashcard;
use App\Models\SesiBelajar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Flashcard>
 */
class FlashcardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sesi_id' => SesiBelajar::factory(),
            'pertanyaan' => fake()->sentence(8).'?',
            'jawaban' => fake()->paragraph(2),
            'urutan' => 0,
        ];
    }
}
