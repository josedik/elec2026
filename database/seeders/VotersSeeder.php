<?php

namespace Database\Seeders;

use App\Models\Voter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VotersSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Voter::factory()
                ->count(50) // Esto creará 50 distritos de ejemplo
                ->create();

    }
}
