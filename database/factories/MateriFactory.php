<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materi>
 */
class MateriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kelas_id'    => Kelas::factory(),
            'pengajar_id' => User::factory()->pengajar(),
            'judul'       => fake()->sentence(5),
            'deskripsi'   => fake()->paragraph(),
            'konten'      => fake()->paragraphs(3, true),
            'tipe'        => 'teks',
            'link_url'    => null,
            'file_path'   => null,
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
}
