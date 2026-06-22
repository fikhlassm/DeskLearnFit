<?php

namespace Database\Factories;

use App\Models\SesiBelajar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SesiBelajar>
 */
class SesiBelajarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->siswa(),
            'metode' => fake()->randomElement(['pomodoro', 'active_recall', 'blurting', 'feynman']),
            'judul' => fake()->sentence(4),
            'durasi_fokus_menit' => fake()->randomElement([25, 30, 45]),
            'durasi_istirahat_menit' => fake()->randomElement([5, 10]),
            'jumlah_siklus' => fake()->numberBetween(1, 6),
            'status' => 'aktif',
            'started_at' => now(),
            'completed_at' => null,
            'catatan' => null,
        ];
    }
}
