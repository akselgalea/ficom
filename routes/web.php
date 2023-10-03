<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
  BecaController,
  CursoController,
  EstudianteController,
  FicomController,
  NivelController,
  UserController,
  PagoController
};
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', function () {
        Inertia::setRootView('layouts.inertia');
        return Inertia::render('Welcome');
    });
    
    Route::prefix('registros')->group(function () {
        Route::get('/subir', function () {
            return view('registros.subir');
        })->name('subidaMasiva');
        
        Route::post('/subir', [EstudianteController::class, 'storeMassive'])->name('subirReg');
    });

    Route::post('check-password', [UserController::class, 'checkPassword'])->name('user.check-password');

    Route::group(['middleware' => ['check.role:admin|contabilidad']], function () {
        //Estudiante
        Route::get('/estudiantes/{id}/becas', [EstudianteController::class, 'becaEdit'])->name('estudiante.beca.edit');
        Route::post('/estudiantes/{id}/becas', [EstudianteController::class, 'becaUpdate'])->name('estudiante.beca.update');
        Route::delete('/estudiantes/{id}/becas', [EstudianteController::class, 'becaDelete'])->name('estudiante.beca.delete');
        Route::get('/estudiantes/{id}/pagos', [EstudianteController::class, 'pagos'])->name('estudiante.pagos');
        Route::post('/estudiantes/{id}/pagos', [EstudianteController::class, 'storePago'])->name('pago.store');
        Route::get('/estudiantes/{id}/generar-reporte', [FicomController::class, 'createReport'])->name('estudiante.ficom.generar');
        Route::get('/estudiantes/generar-reporte', [FicomController::class, 'createReportAll'])->name('estudiante.ficom.generar.all');

        //Pagos
        Route::delete('/pagos/{id}', [PagoController::class, 'delete'])->name('pago.delete');
  
        //Becas
        Route::get('/becas/nueva', [BecaController::class, 'create'])->name('beca.create');
        Route::post('/becas/nueva', [BecaController::class, 'store'])->name('beca.store');
        Route::get('/becas/{id}/editar', [BecaController::class, 'edit'])->name('beca.edit');
        Route::post('/becas/{id}/editar', [BecaController::class, 'update'])->name('beca.update');
        Route::delete('/becas/{id}/borrar', [BecaController::class, 'destroy'])->name('beca.delete');
        
        //Niveles
        Route::prefix('niveles')->group(function () {
            Route::get('/', [NivelController::class, 'index'])->name('nivel.index');
            Route::get('/{id}/cursos', [NivelController::class, 'cursos'])->name('nivel.cursos');
            Route::get('/{id}/editar', [NivelController::class, 'edit'])->name('nivel.edit');
            Route::patch('/{id}/editar', [NivelController::class, 'update'])->name('nivel.update');
        });

        //Cursos
        Route::get('/cursos', [CursoController::class, 'index'])->name('curso.index');
        Route::get('/cursos/{id}', [CursoController::class, 'show'])->name('curso.show');
        Route::get('/cursos/{id}/editar', [CursoController::class, 'edit'])->name('curso.edit');
        Route::post('/cursos/{id}/editar', [CursoController::class, 'update'])->name('curso.update');
        Route::get('/cursos/{id}/generar-reporte-pagos', [CursoController::class, 'createReport'])->name('curso.pagos.generar');
    });
    
    Route::group(['middleware' => ['check.role:admin|matriculas']], function () {
        Route::get('/estudiantes/registro', [EstudianteController::class, 'registroEscolar'])->name('estudiante.registro');
        Route::get('/estudiantes/nuevo', [EstudianteController::class, 'create'])->name('estudiante.create');
        Route::get('/estudiantes/{id}', [EstudianteController::class, 'show'])->name('estudiante.show');
        Route::delete('/estudiantes/{id}', [EstudianteController::class, 'destroy'])->name('estudiante.delete');
        Route::post('/estudiantes/crear', [EstudianteController::class, 'store'])->name('estudiante.store');
        Route::post('/estudiantes/{id}/editar', [EstudianteController::class, 'update'])->name('estudiante.update');
        Route::delete('/estudiantes/{id}/apoderados/{apoderado}', [EstudianteController::class, 'apoderadoRemove'])->name('estudiante.apoderado.remove');
    });
    
    //Estudiante
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiante.index');
    Route::get('/estudiantes/nuevos', [EstudianteController::class, 'getEstudiantesNuevos'])->name('estudiante.nuevos');
    
    //Becas
    Route::get('/becas', [BecaController::class, 'index'])->name('beca.index');
    Route::get('/becas/{id}', [BecaController::class, 'show'])->name('beca.show');

    Route::group(['middleware' => ['check.role:admin']], function () {
      Route::get('/usuarios', [UserController::class, 'index'])->name('usuario.index');
      Route::get('/usuarios/bitacora', [UserController::class, 'bitacora'])->name('usuario.bitacora');
      Route::get('/usuarios/nuevo', [UserController::class, 'create'])->name('usuario.create');
      Route::get('/usuarios/{id}', [UserController::class, 'get'])->name('usuario.get');
      Route::post('/usuarios', [UserController::class, 'store'])->name('usuario.store');
      Route::patch('/usuarios/{id}', [UserController::class, 'update'])->name('usuario.update');
      Route::delete('/usuarios/{id}', [UserController::class, 'delete'])->name('usuario.delete');
    });
});
