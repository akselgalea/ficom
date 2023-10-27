<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Beca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Inertia\Inertia;
use App\Exports\RegistroEscolarExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\EstudianteService;
use Exception;

class EstudianteController extends Controller
{
    private $estud;
    private $studentService;

    public function __construct(Estudiante $estud, EstudianteService $studentService)
    {
        $this->estud = $estud;
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {   
        Inertia::setRootView('layouts.inertia');
        $estudiantes = $this->studentService->index($req, request('perPage', 10), request('curso', 'todos'));

        return Inertia::render('Estudiante/Index', ['estudiantes' => $estudiantes, 'cursos' => Curso::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $req)
    {  
        $result = $this->studentService->store($req);

        if(isset($result['status']))
            return response()->json(['message' => $result['message']], $result['status']);

        return response()->json($result, 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        Inertia::setRootView('layouts.inertia'); 
        
        return Inertia::render('Estudiante/Actualizar', ['estudiante' => $this->studentService->perfil($id), 'cursos' => Curso::all()]);
    }

    public function getEstudiantesNuevos(Request $req) {
        $perPage = request('perPage', '10');
        $estudiantes = $this->estud::with('curso')->where('es_nuevo', true)->latest()->paginate($perPage);
        
        return view('estudiante.listar')->with(['estudiantes' => $estudiantes, 'cursos' => Curso::all(), 'perPage' => $perPage]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $req)
    {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Estudiante/Crear', ['cursos' => Curso::all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estudiante  $estudiante
     */
    public function edit($id)
    {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Estudiante/Actualizar', ['estudiante' => $this->studentService->findbyId($id), 'cursos' => Curso::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estudiante  $estudiante
     */
    public function update($id, Request $req)
    {
        return redirect()->back()->with('res', $this->studentService->update($id, $req));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estudiante  $estudiante
     */
    public function destroy($id)
    {
        $response = $this->studentService->delete($id);
        return response()->json(['message' => $response['message']], $response['status']);
    }


    /* Pagos ------------------------------------------- */
    /**
     * Store massively resources in storage.
     *
     * @param int   $id
     * @param  \Illuminate\Http\Request  $request
     */
    public function storePago($id, Request $req) {
        $result = $this->studentService->pagoStore($id, $req);
        return response()->json($result, $result['status']);
    }

    public function pagos($id)
    {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Estudiante/Pagos/Index', $this->studentService->pagosYear($id));
    }

    public function pagosYear($id, $year) {
        $res = $this->studentService->pagosYear($id, $year);
        
        return response()->json($res, $res['status'] ?? 200);        
    }

    public function becaEdit($id) {
        return view('estudiante.beca', ['estudiante' => $this->studentService->findById($id), 'becas' => Beca::all()]);
    }

    public function becaUpdate($id, Request $req) {
        return redirect()->back()->with('res', $this->studentService->becaUpdate($id, $req));
    }

    public function becaDelete($id) {
        return redirect()->back()->with('res', $this->studentService->becaDelete($id));
    }

    public function apoderadoRemove($id, $apoderado) {
        return $this->estud->apoderadoRemove($id, $apoderado);
    }

    public function registroEscolar() {
      return Excel::download(new RegistroEscolarExport(), "Registro escolar - " . now()->year . ".xlsx");
    }
    
    /**
     * Store massively resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function storeMassive(Request $request)
    {
        try {
            $request->validate([
                "tipoRegistro"=>"required",
                "file"=>"required"
            ]);

            if($request->tipoRegistro == "nomina"){

                $request->validate(["file"=> "mimes:xml"]);
                $file = $request->file('file');
                $a = Storage::disk('local')->put('docs',$file);
                $process = new Process([
                    'C:\Users\aksel\AppData\Local\Programs\Python\Python311\python.exe',
                    // 'python',
                    // 'python3', // para linux
                    storage_path('app/xml/dataConverter.py'),
                    storage_path('app/'.$a)
                ]);
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                return redirect()->back()->with('res', ['status' => 200, 'message' => 'Registros subidos con éxito']);
            }
            elseif($request->tipoRegistro == "prioritarios"){
                $request->validate(["file"=> "mimes:xlsx"]);
                $file = $request->file('file');
                $a = Storage::disk('local')->put('docs',$file);
                $process = new Process([
                    'C:\Users\aksel\AppData\Local\Programs\Python\Python311',
                    // 'python',
                    // 'python3', // para linux
                    storage_path('app/xml/dataConverter2.py'),
                    storage_path('app/'.$a)
                ]);
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                return redirect()->back()->with('res', ['status' => 200, 'message' => 'Registros subidos con éxito']);
            }
            else{
                return redirect()->back()->with('res', ['status' => 400, 'message' => 'Error al subir el registro']);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}