<?php

namespace Database\Factories;

use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FlashcardReview>
 */
class FlashcardReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'flashcard_id' => Flashcard::factory(),
            'sesi_id' => SesiBelajar::factory(),
            'user_id' => User::factory()->siswa(),
            'benar' => fake()->boolean(70),
            'reviewed_at' => now(),
        ];
    }
}
