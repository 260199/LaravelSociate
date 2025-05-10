<?php

namespace Database\Seeders;

use App\Models\Daily;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Daily::factory()->count(20)->create(); // Buat 100 data daily acak
    }
}
