<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 10 admin (role 1) dan 40 user (role 2)
        User::factory()->count(8)->create(['role' => '1']);
        User::factory()->count(8)->create(['role' => '2']);
    }
}