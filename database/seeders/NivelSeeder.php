<?php

namespace Database\Seeders;

use App\Models\{Nivel, NivelCosto};
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
        Nivel::create(['nombre' => 'Enseñanza Pre-básica']);
        Nivel::create(['nombre' => 'Enseñanza Básica']);
        Nivel::create(['nombre' => 'Enseñanza Media']);
        Nivel::create(['nombre' => 'Cuarto Medio']);

        NivelCosto::create(['matricula' => 100000, 'arancel' => 300000, 'periodo' => 2023, 'nivel_id' => 1]);
        NivelCosto::create(['matricula' => 150000, 'arancel' => 500000, 'periodo' => 2023, 'nivel_id' => 2]);
        NivelCosto::create(['matricula' => 200000, 'arancel' => 600000, 'periodo' => 2023, 'nivel_id' => 3]);
        NivelCosto::create(['matricula' => 200000, 'arancel' => 650000, 'periodo' => 2023, 'nivel_id' => 4]);
    }
}
