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
             'google_id' => fake()->unique()->uuid(),
             'name' => fake()->name(),
             'role' => fake()->randomElement(['1', '2']),
             'email' => fake()->unique()->safeEmail(),
             'password' => bcrypt('password'),
             'is_password_set' => true,
             'email_verified_at' => now(),
             'remember_token' => Str::random(10),
         ];
     }
 
     public function unverified(): static
     {
         return $this->state(fn (array $attributes) => [
             'email_verified_at' => null,
         ]);
     }
 }