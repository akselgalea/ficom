<?php

namespace App\Exports;

use App\Models\Estudiante;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstudiantesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $id;
    private $year;

    public function __construct($id = null, $year = null) {
        $this->id = $id;
        $this->year = $year ?? now()->year;
    }

    public function view(): View
    {
        $registros = [];

        if($this->id) {
            array_push($registros, Estudiante::find($this->id)->registrosFicom($this->year));
        }
        
        else {
            $estudiantes = Estudiante::all();

            foreach($estudiantes as $estudiante) {
                array_push($registros, $estudiante->registrosFicom($this->year));
            }
        }

        return view('registros.export', ['registros' => $registros]);
    }
}
