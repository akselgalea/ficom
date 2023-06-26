<?php

namespace Database\Seeders;

use App\Models\Nivel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nivel::create(['nombre' => 'Enseñanza Pre-básica', 'matricula' => 100000, 'arancel' => 30000]);
        Nivel::create(['nombre' => 'Enseñanza Básica', 'matricula' => 150000, 'arancel' => 50000]);
        Nivel::create(['nombre' => 'Enseñanza Media', 'matricula' => 200000, 'arancel' => 60000]);
    }
}
