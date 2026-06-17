<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tugas>
 */
class TugasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kelas_id'    => Kelas::factory(),
            'pengajar_id' => User::factory()->pengajar(),
            'judul'       => fake()->sentence(5),
            'deskripsi'   => fake()->paragraph(),
            'deadline'    => now()->addDays(7),
            'status'      => 'draf',
            'published_at' => null,
        ];
    }

    public function terbit(): static
    {
        return $this->state(fn () => [
            'status'       => 'terbit',
            'published_at' => now(),
        ]);
    }

    public function ditutup(): static
    {
        return $this->state(fn () => ['status' => 'ditutup']);
    }
}
