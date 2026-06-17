<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JurnalBelajar>
 */
class JurnalBelajarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'               => User::factory()->siswa(),
            'tanggal'               => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'judul'                 => fake()->sentence(4),
            'isi_jurnal'            => fake()->paragraph(3),
            'metode_yang_digunakan' => fake()->randomElement(['pomodoro', 'active_recall', 'blurting', 'feynman']),
            'rating_efektivitas'    => fake()->numberBetween(1, 5),
            'durasi_menit'          => fake()->numberBetween(15, 120),
        ];
    }
}
