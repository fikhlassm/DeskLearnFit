<?php

namespace Database\Factories;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnggotaKelas>
 */
class AnggotaKelasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::factory(),
            'siswa_id' => User::factory()->siswa(),
            'joined_at' => now(),
        ];
    }
}
