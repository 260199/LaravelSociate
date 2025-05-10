<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Daily>
 */
class DailyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(), // fallback ke factory kalau belum ada user
            'kegiatan' => fake()->sentence(3),
            'jekeg_id' => \App\Models\jekeg::inRandomOrder()->first()?->id ?? \App\Models\Jekeg::factory(),
            'deskripsi' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['progress', 'dilaporkan','diterima']),
            'done_at' => now()->subDays(rand(0, 7)),
        ];
    }
}
