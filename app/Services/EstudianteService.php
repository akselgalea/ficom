<?php
namespace App\Services;

use App\Models\{Estudiante, Pago};
use Freshwork\ChileanBundle\Rut;
use Freshwork\ChileanBundle\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecordatorioPago;
use Exception;

class EstudianteService
{
    function index($req, $perPage, $curso) {
        //Busqueda y firtrado por Temas
        if ($curso != 'todos') {
            if ($req->search) {
                return Estudiante::with('curso')
                    ->searchByName($req->search)
                    ->searchBySurname($req->search)
                    ->searchByRut($req->search)
                    ->searchByCurso($curso)
                    ->paginate($perPage);
            }

            return Estudiante::with(['curso'])->where('curso_id', $curso)->paginate($perPage);
        }

        if ($req->search) {
            return Estudiante::with(['curso'])
                ->searchByName($req->search)
                ->searchBySurname($req->search)
                ->searchByRut($req->search)
                ->paginate($perPage);
        }

        return Estudiante::with(['curso'])->paginate($perPage);
    }

    function findById($id) {
        return Estudiante::findOrFail($id);
    }

    function perfil($id) {
        // era show
        $estudiante = $this->findById($id);
        $apellidos = explode(' ', $estudiante->apellidos);
        $estudiante->apellido_paterno = $apellidos[0];
        $estudiante->apellido_materno = $apellidos[1] ?? '';
        $estudiante->run = $estudiante->rut . '-' . $estudiante->dv;

        return $estudiante;
    }

    // Pagos
    function pagosIndex($id) {
        $estudiante = $this->findById($id);
        $estudiante->pagos = $estudiante->pagos()->get();

        return $estudiante;
    }

    function pagosYear($id, $year = null) {
        $lastYear = $year;
        if(!$year) $year = date('Y');
        $estudiante = $this->findById($id);

        return [
            'estudiante' => $estudiante,
            'pagos' => $estudiante->pagosPorAnio($year),
            'mensualidad' => $estudiante->getTotalAPagarPorMes($year),
            'periodoAnterior' => $lastYear ? $estudiante->getPeriodoYear($year) : null
        ];
    }

    function pagoStore($id, $req) {
        $estudiante = $this->findById($id);
        $costoMensualidad = $estudiante->getTotalAPagarPorMes($req->anio);

        if($estudiante->getPeriodoYear($req->anio)->prioridad == 'prioritario') return ['status' => 400, 'message' => 'Los estudiantes prioritarios no deben pagar'];

        $esRecibo = $req->documento === 'recibo';
        
        $minPago = $esRecibo ? $costoMensualidad : 1;
        $maxPago = $estudiante->totalAPagar($req->anio, $req->mes);
        
        if($maxPago <= 0) return ['status' => 400, 'message' => 'Este mas ya ha sido pagado completamente'];

        $validated = $req->validate(
            Pago::rules($maxPago, $minPago),
            Pago::messages($req->mes, $maxPago, $esRecibo),
            Pago::attributes()
        );

        try {
            $pago = $estudiante->pagos()->create($validated);
            
            return ['status' => 200, 'message' => 'Pago registrado con éxito', 'pago' => $pago];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => 'Ha ocurrido un error'];
        }
    }
    

    // Beca
    function becaUpdate ($id, $req) {
        try {
            $estudiante = $this->findById($id);

            if ($estudiante->prioridad == 'prioritario')
                return ['status' => 400, 'message' => 'No se puede asignar becas a un estudiante prioritario'];

            $estudiante->beca()->associate($req->beca_id)->save();
            return ['status' => 200, 'message' => 'Beca asignada con éxito'];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => 'Ha ocurrido un error', 'datos' => $req->except('_token')];
        }
    }

    public function becaDelete($id)
    {
        try {
            $this->findById($id)->beca()->dissociate()->save();
            return ['status' => 200, 'message' => 'Beca removida con éxito'];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => 'Ha ocurrido un error'];
        }
    }


    // CRUD
    function store($req) {
        $hasApoderado = true;
        $hasApoderadoSuplente = true;
        $hasMadre = !empty($req->madre);
        $hasPadre = !empty($req->padre);
        
        $req->validate(
            Estudiante::rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre),
            Estudiante::messages(),
            Estudiante::attributes()
        );

        try {
            $formattedRut = $this->validateRut($req->estudiante['run']);

            $estudiante = Estudiante::create([
                'nombres' => $req->estudiante['nombres'],
                'apellidos' => $req->estudiante['apellido_paterno'] . ' ' . $req->estudiante['apellido_materno'],
                'rut' => $formattedRut[0],
                'dv' => $formattedRut[1],
                'es_nuevo' => 1,
                'edad' => $req->estudiante['edad'],
                'genero' => $req->estudiante['genero'],
                'direccion' => $req->estudiante['direccion'],
                'nacionalidad' => $req->estudiante['nacionalidad'],
                'fecha_nacimiento' => $req->estudiante['fecha_nacimiento'],
                'email' => $req->estudiante['email'],
                'enfermedades' => $req->estudiante['enfermedades'],
                'persona_emergencia' => $req->estudiante['persona_emergencia'],
                'telefono_emergencia' => $req->estudiante['telefono_emergencia'],
                'apoderados' => [
                    'apoderado_titular' => $req->apoderado_titular,
                    'apoderado_suplente' => empty($req->apoderado_suplente) ? null : $req->apoderado_suplente,
                    'madre' => empty($req->madre) ? null : $req->madre,
                    'padre' => empty($req->padre) ? null : $req->padre,
                    'suplentes' => $req->suplentes
                ]
            ]);

            $estudiante->periodo()->create([
                'periodo' => now()->year,
                'prioridad' => $req->estudiante['prioridad'],
                'curso_id' => $req->estudiante['nivel']
            ]);

            return ['status' => 200, 'message' => 'Estudiante creado con éxito'];
        } catch (InvalidFormatException $e) {
            $message = "RUT Incorrecto";
            return ['status' => 400, 'message' => $message];
        } catch (Exception $e) {
            $message = 'Ha ocurrido un error';

            if (str_contains($e->getMessage(), 'apoderado'))
                $message = $e->getMessage();

            if (str_contains($e->getMessage(), 'estudiantes_rut_unique'))
                $message = 'Este estudiante ya se encuentra registrado';

            return ['status' => 400, 'message' => $message];
        }
    }

    function update($id, $req) {
        $estudiante = Estudiante::findOrFail($id);
        $formattedRut = $this->validateRut($req->estudiante['run']);

        $hasApoderado = true;
        $hasApoderadoSuplente = true;
        $hasMadre = !empty($req->madre);
        $hasPadre = !empty($req->padre);
        
        $req->validate(
            Estudiante::rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre, $id),
            Estudiante::messages(),
            Estudiante::attributes()
        );

        try {
            $estudiante->update([
                'apellidos' => $request->estudiante['a,ellido_paterno'] . ' ' . $request->estudiante['apellido_materno'],
                'nombres' => $request->estudiante['nombres'],
                'rut' => $formattedRut[0],
                'dv' => $formattedRut[1],
                'prioridad' => $request->estudiante['prioridad'],
                'edad' => $request->estudiante['edad'],
                'genero' => $request->estudiante['genero'],
                'nacionalidad' => $request->estudiante['nacionalidad'],
                'direccion' => $request->estudiante['direccion'],
                'telefono' => $request->estudiante['telefono'],
                'fecha_nacimiento' => $request->estudiante['fecha_nacimiento'],
                'email' => $request->estudiante['email'],
                'enfermedades' => $request->estudiante['enfermedades'],
                'persona_emergencia' => $request->estudiante['persona_emergencia'],
                'telefono_emergencia' => $request->estudiante['telefono_emergencia'],
                'apoderados' => [
                'apoderado_titular' => $request->apoderado_titular,
                'apoderado_suplente' => empty($request->apoderado_suplente) ? null : $request->apoderado_suplente,
                'madre' => empty($request->madre) ? null : $request->madre,
                'padre' => empty($request->padre) ? null : $request->padre,
                'suplentes' => $request->suplentes
                ]
            ]);

            $estudiante->periodoActual->update(['curso_id' => $req->estudiante['nivel']]);

            if($req->estudiante['prioridad'] === 'prioritario') {
                $estudiante->periodoActual->beca()->dissociate();
            }

            return ['status' => 200, 'message' => 'Estudiante actualizado con exito!'];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => $e->getMessage()];
        }
    }

    function delete($id) {
        try {
            Estudiante::findOrFail($id)->delete();

            return ['status' => 200, 'message' => 'Estudiante eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    // Funciones reciclables
    function validateRut($rut) {
        Rut::parse($rut)->validate();
        $rut = Rut::parse($rut)->format(Rut::FORMAT_ESCAPED);
        return Rut::parse($rut)->toArray();
    }

    function recordatorioDePago($id) {
        try {
            $estudiante = Estudiante::findOrFail($id);
    
            $totalAPagar = $estudiante->getTotalAPagarPorMes();
            
            if($totalAPagar == 0) 
                return false;
            
            $mes = _monthToString(date('m'));
            $apoderado = $estudiante->getApoderado();
    
            if(!$apoderado) 
                return false;
    
            $datosPago = [
                'mes' => $mes,
                'arancel' => $estudiante->curso->nivel->arancel,
                'totalDescuentos' => $estudiante->getDescuentos(),
                'abonado' => $estudiante->totalPagadoMes(date('Y'), $mes),
                'totalPagar' => $estudiante->getTotalAPagarPorMes()
            ];
    
            Mail::mailer("smtp")->to($apoderado->email)->send(new RecordatorioPago($estudiante, $apoderado, $datosPago));
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}