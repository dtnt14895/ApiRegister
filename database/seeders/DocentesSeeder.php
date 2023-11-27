<?php

namespace Database\Seeders;

use App\Models\Docentes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Docentes::factory(10)->create();
    }
}
