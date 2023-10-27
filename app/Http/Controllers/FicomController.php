<?php

namespace App\Http\Controllers;

use App\Exports\EstudiantesExport;
use App\Models\Estudiante;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class FicomController extends Controller
{
    private $est;
    
    public function __construct(Estudiante $est)
    {
        $this->est = $est;
    }


    public function createReport($id, Request $req) {
        $queryPeriodo = $req->query('periodo');
        $year = $queryPeriodo ? intval($queryPeriodo) : null;
        return Excel::download(new EstudiantesExport($id, $year), "{$this->est->find($id)->rut}-reporte-ficom.xlsx");
    }

    public function createReportAll() {
        return Excel::download(new EstudiantesExport, "reporte-ficom-todos.xlsx");
    }
}
