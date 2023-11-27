<?php

namespace Database\Seeders;

use App\Models\Alumnos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlumnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Alumnos::factory(10)->create();
    }
}
