<?php

namespace App\Exports;

use App\Models\Curso;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CursosExport implements FromView
{
    private $id;

    public function __construct($id = null,) {
        $this->id = $id;
    }

    public function view(): View
    {
        $registros = Curso::find($this->id)->informeMontoPendiente();

        return view('registros.cursos.reporte-pagos', ['registros' => $registros]);
    }
}
