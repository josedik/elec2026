<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Jose Gamarra',
            'email' => 'jgamarra@example.com',
            'password' => bcrypt('admin1234')
        ])->assignRole('Admin');
        User::create([
            'name' => 'Flor Lizzetti',
            'email' => 'flor@example.com',
            'password' => bcrypt('admin1234')
        ])->assignRole('User');

        User::factory(8)
            ->create();

    }
}
