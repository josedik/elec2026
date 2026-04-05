<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         School::factory()
                ->count(500) // Esto creará 50 distritos de ejemplo
                ->create();
    }
}
