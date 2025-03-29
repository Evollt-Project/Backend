<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create([
            'title' => 'Для начинающих',
            'level' => 1
        ]);
        Level::create([
            'title' => 'Для продолжающих',
            'level' => 2
        ]);
        Level::create([
            'title' => 'Для профи',
            'level' => 3
        ]);
    }
}
