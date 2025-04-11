<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        {
            $user=[
                [
            'name' => ' Ilham Mubara',
            'username' => '260199',
            'email' => 'nanovstar2@gmail.com',
            'password' => bcrypt('akuaja001'),
            'role' => 1,
                ]];
                foreach($user as $key => $value){
                    User::create($value);
                };
            }
        }
}
