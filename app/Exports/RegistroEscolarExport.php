<?php

namespace App\Exports;

use App\Models\Estudiante;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RegistroEscolarExport implements FromView
{
    public function view(): View
    {
        $estudiantes = Estudiante::withTrashed()->get();

        return view('registros.index', ['estudiantes' => $estudiantes]);
    }
}
