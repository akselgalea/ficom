<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curso = new Curso([
            'curso'=> 'PK',
            'paralelo' => 'A',
            'nivel_id' => 1
        ]);
        $curso->save();

        $curso = new Curso([
            'curso'=> 'PK',
            'paralelo' => 'B',
            'nivel_id' => 1
        ]);
        $curso->save();

        $curso = new Curso([
            'curso'=> 'K',
            'paralelo' => 'A',
            'nivel_id' => 1
        ]);
        $curso->save();

        $curso = new Curso([
            'curso'=> 'K',
            'paralelo' => 'B',
            'nivel_id' => 1
        ]);
        $curso->save();

        $curso = new Curso([
            'curso'=> '1',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();

        $curso = new Curso([
            'curso'=> '1',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();

        $curso = new Curso([
            'curso'=> '2',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '2',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '3',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '3',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '4',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '4',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '5',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '5',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '6',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '6',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '7',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '7',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '8',
            'paralelo' => 'A',
            'nivel_id' => 2
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '8',
            'paralelo' => 'B',
            'nivel_id' => 2
        ]);
        $curso->save();


        $curso = new Curso([
            'curso'=> '1M',
            'paralelo' => 'A',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '1M',
            'paralelo' => 'B',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '2M',
            'paralelo' => 'A',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '2M',
            'paralelo' => 'B',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '3M',
            'paralelo' => 'A',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '3M',
            'paralelo' => 'B',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '4M',
            'paralelo' => 'A',
            'nivel_id' => 3
        ]);
        $curso->save();
        $curso = new Curso([
            'curso'=> '4M',
            'paralelo' => 'B',
            'nivel_id' => 3
        ]);
        $curso->save();
    }
}
