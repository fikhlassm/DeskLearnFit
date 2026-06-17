<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'role'              => 'siswa',
            'quiz_result'       => null,
            'quiz_scores'       => null,
            'remember_token'    => Str::random(10),
        ];
    }

    /** State: siswa tanpa quiz result. */
    public function siswa(): static
    {
        return $this->state(fn () => [
            'role'        => 'siswa',
            'quiz_result' => null,
            'quiz_scores' => null,
        ]);
    }

    /** State: siswa yang sudah mengerjakan quiz. */
    public function siswaWithQuiz(): static
    {
        return $this->state(fn () => [
            'role'        => 'siswa',
            'quiz_result' => 'pomodoro',
            'quiz_scores' => [
                'pomodoro'      => 12,
                'active_recall' => 8,
                'blurting'      => 6,
                'feynman'       => 5,
            ],
        ]);
    }

    /** State: pengajar. */
    public function pengajar(): static
    {
        return $this->state(fn () => [
            'role'        => 'pengajar',
            'quiz_result' => null,
            'quiz_scores' => null,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
