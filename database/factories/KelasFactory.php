<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pengajar_id'    => User::factory()->pengajar(),
            'nama_kelas'     => fake()->words(3, true),
            'mata_pelajaran' => fake()->randomElement(['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia']),
            'kode_kelas'     => strtoupper(fake()->unique()->lexify('???-###')),
            'deskripsi'      => fake()->sentence(),
            'kapasitas'      => fake()->numberBetween(10, 40),
            'status'         => 'aktif',
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn () => ['status' => 'aktif']);
    }

    public function draf(): static
    {
        return $this->state(fn () => ['status' => 'draf']);
    }

    public function milikPengajar(int $pengajarId): static
    {
        return $this->state(fn () => ['pengajar_id' => $pengajarId]);
    }
}
