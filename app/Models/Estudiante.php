<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;
use Freshwork\ChileanBundle\Exceptions\InvalidFormatException;
use Freshwork\ChileanBundle\Rut;
use Exception;
use Illuminate\Validation\Rule;
use App\Mail\RecordatorioPago;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estudiante extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'id',
        'apellidos',
        'nombres',
        'email',
        'genero',
        'rut',
        'edad',
        'fecha_nacimiento',
        'nacionalidad',
        'enfermedades',
        'persona_emergencia',
        'telefono_emergencia',
        'dv',
        'es_nuevo',
        'prioridad',
        'email_institucional',
        'telefono',
        'direccion',
        'apoderados',
        'curso_id',
        'beca_id'
    ];

    protected $casts = [
      'apoderados' => 'array'
    ];

    /**
     * Get the apoderados that owns the Estudiante
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apoderados(): BelongsToMany
    {
        return $this->belongsToMany(Apoderado::class, 'apoderado_estudiante', 'estudiante_id', 'apoderado_id')->withPivot('es_suplente');
    }

    public function apoderadoTitular()
    {
        return $this->apoderados()->wherePivot('es_suplente', false);
    }

    public function apoderadoSuplente()
    {
        return $this->apoderados()->wherePivot('es_suplente', true);
    }

    public function beca(): BelongsTo
    {
        return $this->belongsTo(Beca::class);
    }

    /**
     * Get the curso that owns the Estudiante
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Get all of the pagos for the Estudiante
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function hasApoderadoTitular() : bool {
        return $this->apoderadoTitular->count() > 0;
    }

    public function hasApoderadoSuplente(): bool {
        return $this->apoderadoSuplente->count() > 0;
    }

    public function scopeSearchByCurso($query, $curso)
    {
        $query->where('curso_id', '=', $curso);
    }

    public function scopeSearchByName($query, $text)
    {
        $query->where('nombres', 'LIKE', "%$text%");
    }

    public function scopeSearchBySurname($query, $text)
    {
        $query->orWhere('apellidos', 'LIKE', "%$text%");
    }

    public function scopeSearchByRut($query, $text)
    {
        $query->orWhere('rut', 'LIKE', "%$text%");
    }

    public function getReporteFicomAttribute($year = null) {
        $anio = $year ?? now()->year;

        return [
            'RBD' => env('RBD'),
            'Posee RUN' => $this->poseeRun(),
            'RUN alumno' => $this->rut . '-' . $this->dv,
            'DV alumno' => $this->dv,
            'Annio mensualidad percibida' => $anio,
            'Monto total mensualidad' => $this->totalMensualidades($anio),
            'Monto total intereses y/o gastos de cobranza' => 0,
            'Cantidad de mensualidades' => $this->mesesPagados($anio),
            'Tipo de Documento' => env('TIPO_DOCUMENTO')
        ];
    }

    private function rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre, $id = null): array {
        $rules = [
            'estudiante.nombres' => 'required|max:255',
            'estudiante.apellidos' => 'sometimes|required|max:255',
            'estudiante.apellido_paterno' => 'sometimes|required|max:255',
            'estudiante.apellido_materno' => 'sometimes|required|max:255',
            'estudiante.genero' => [Rule::in(['M', 'F', 'N']), 'required'],
            'estudiante.edad' => 'required|int',
            'estudiante.fecha_nacimiento' => 'required|date',
            'estudiante.enfermedades' => 'nullable|string',
            'estudiante.persona_emergencia' => 'nullable|string',
            'estudiante.telefono_emergencia' => 'nullable|string',
            'estudiante.nacionalidad' => 'required|string|min:3|max:50',
            'estudiante.run' => 'required|max:10',
            'estudiante.email' => ['required', 'email', Rule::unique('estudiantes', 'email')->ignore($id)],
            'estudiante.email_institucional' => ['sometimes', 'nullable', 'email', Rule::unique('estudiantes', 'email_institucional')->ignore($id)],
            'estudiante.nivel' => 'required',
            'estudiante.prioridad' => 'required',
            'apoderado_titular.nombre' => Rule::requiredIf($hasApoderado),
            'apoderado_titular.rut' => Rule::requiredIf($hasApoderado),
            'apoderado_titular.telefono' => [Rule::requiredIf($hasApoderado), 'min:8', 'max:12', 'nullable'],
            'apoderado_titular.email' => [Rule::requiredIf($hasApoderado), 'email', 'nullable'],
            'apoderado_titular.direccion' => Rule::requiredIf($hasApoderado),
            'apoderado_suplente.nombre' => Rule::requiredIf($hasApoderadoSuplente),
            'apoderado_suplente.rut' => Rule::requiredIf($hasApoderadoSuplente),
            'apoderado_suplente.telefono' => [Rule::requiredIf($hasApoderadoSuplente), 'min:8', 'max:12', 'nullable'],
            'apoderado_suplente.email' => [Rule::requiredIf($hasApoderadoSuplente), 'email', 'nullable'],
            'apoderado_suplente.direccion' => Rule::requiredIf($hasApoderadoSuplente),
            'madre.nombre' => Rule::requiredIf($hasMadre),
            'madre.rut' => Rule::requiredIf($hasMadre),
            'madre.telefono' => [Rule::requiredIf($hasMadre), 'min:8', 'max:12', 'nullable'],
            'madre.email' => [Rule::requiredIf($hasMadre), 'email', 'nullable'],
            'madre.direccion' => Rule::requiredIf($hasMadre),
            'padre.nombre' => Rule::requiredIf($hasPadre),
            'padre.rut' => Rule::requiredIf($hasPadre),
            'padre.telefono' => [Rule::requiredIf($hasPadre), 'min:8', 'max:12', 'nullable'],
            'padre.email' => [Rule::requiredIf($hasPadre), 'email', 'nullable'],
            'padre.direccion' => Rule::requiredIf($hasPadre),
            'suplentes.*.nombre' => 'required|max:255',
            'suplentes.*.rut' => 'required|max:10',
            'suplentes.*.telefono' => 'required|min:8|max:12|nullable',
            'suplentes.*.email' => 'required|email',
            'suplentes.*.direccion' => 'required|max:255',
        ];
        
        return $rules;
    }

    private function messages(): array {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'email_institucional.unique' => 'Este correo ya esta en uso',
            'min' => 'El campo :attribute requiere un minimo de :min caracteres',
            'max' => 'El campo :attribute requiere un maximo de :max caracteres'
        ];
    }

    private function attributes(): array {
        return [
            'estudiante.nombres' => 'nombres',
            'estudiante.apellidos' => 'apellidos',
            'estudiante.apellido_paterno' => 'apellido paterno',
            'estudiante.apellido_materno' => 'apellido materno',
            'estudiante.run' => 'run',
            'estudiante.edad' => 'edad',
            'estudiante.nacionalidad' => 'nacionalidad',
            'estudiante.fecha_nacimiento' => 'fecha de nacimiento',
            'estudiante.enfermedades' => 'enfermedades y contraindicaciones',
            'estudiante.persona_emergencia' => 'remitir a',
            'estudiante.telefono_emergencia' => 'N° Telefónico',
            'estudiante.genero' => 'género',
            'estudiante.email' => 'email',
            'estudiante.email_institucional' => 'correo institucional',
            'estudiante.nivel' => 'nivel',
            'estudiante.prioridad' => 'prioridad',
            'apoderado_titular.nombre' => 'nombre',
            'apoderado_titular.rut' => 'rut',
            'apoderado_titular.telefono' => 'telefono',
            'apoderado_titular.email' => 'email',
            'apoderado_titular.direccion' => 'direccion',
            'apoderado_suplente.nombre' => 'nombre',
            'apoderado_suplente.rut' => 'rut',
            'apoderado_suplente.telefono' => 'telefono',
            'apoderado_suplente.email' => 'email',
            'apoderado_suplente.direccion' => 'direccion',
            'madre.nombre' => 'nombre',
            'madre.rut' => 'rut',
            'madre.telefono' => 'telefono',
            'madre.email' => 'email',
            'madre.direccion' => 'direccion',
            'padre.nombre' => 'nombre',
            'padre.rut' => 'rut',
            'padre.telefono' => 'telefono',
            'padre.email' => 'email',
            'padre.direccion' => 'direccion',
            'suplentes.*.nombre' => 'nombre',
            'suplentes.*.rut' => 'rut',
            'suplentes.*.telefono' => 'telefono',
            'suplentes.*.email' => 'email',
            'suplentes.*.direccion' => 'direccion'
        ];
    }

    public function index($req)
    {
        $perPage = request('perPage', 10);
        $curso = request('curso', 'todos');
        //Busqueda y firtrado por Temas
        if ($curso != 'todos') {
            if ($req->search) {

                $estudiantes = Estudiante::with('curso')
                    ->searchByName($req->search)
                    ->searchBySurname($req->search)
                    ->searchByRut($req->search)
                    ->searchByCurso($curso)
                    ->paginate($perPage);

                return ['estudiantes' => $estudiantes, 'perPage' => $perPage];
            }

            return ['estudiantes' => Estudiante::with(['curso'])->where('curso_id', $curso)->paginate($perPage), 'perPage' => $perPage];
        }

        //Solo Busqueda
        if ($req->search) {
            return [
                'estudiantes' => Estudiante::with(['curso'])
                    ->searchByName($req->search)
                    ->searchBySurname($req->search)
                    ->searchByRut($req->search)
                    ->paginate($perPage),
                'perPage' => $perPage
            ];
        }

        return ['estudiantes' => Estudiante::with('curso')->paginate($perPage), 'perPage' => $perPage];
    }

    public function show($id)
    {
        $estudiante = Estudiante::with('curso', 'beca')->findOrFail($id);
        $estudiante["apoderado_titular"] = $estudiante->apoderadoTitular()->first();
        $estudiante["apoderado_suplente"] = $estudiante->apoderadoSuplente()->first();
        $estudiante->recordatorioDePago($id);

        return ['estudiante' => $estudiante, 'cursos' => Curso::all(), 'becas' => Beca::all()];
    }

    public function store($req)
    {   
        $hasApoderado = !empty($req->apoderado_titular);
        $hasApoderadoSuplente = true;
        $hasMadre = !empty($req->madre);
        $hasPadre = !empty($req->padre);
        
        $req->validate(
            $this->rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre),
            $this->messages(),
            $this->attributes()
        );
        
        try {
            Rut::parse($req->estudiante['run'])->validate();
            $rut = Rut::parse($req->estudiante['run'])->format(Rut::FORMAT_ESCAPED);
            $rut = Rut::parse($rut)->toArray();

            //Estudiante
            $estudiante = new Estudiante();
            $estudiante->nombres = $req->estudiante['nombres'];
            $estudiante->apellidos = $req->estudiante['apellido_paterno'] . ' ' . $req->estudiante['apellido_materno'];
            $estudiante->rut = $rut[0];
            $estudiante->dv = $rut[1];
            $estudiante->es_nuevo = 1;
            $estudiante->edad = $req->estudiante['edad'];
            $estudiante->genero = $req->estudiante['genero'];
            $estudiante->direccion = $req->estudiante['direccion'];
            $estudiante->nacionalidad = $req->estudiante['nacionalidad'];
            $estudiante->curso_id = $req->estudiante['nivel'];
            $estudiante->prioridad = $req->estudiante['prioridad'];
            $estudiante->fecha_nacimiento = $req->estudiante['fecha_nacimiento'];
            $estudiante->email = $req->estudiante['email'];
            $estudiante->enfermedades = $req->estudiante['enfermedades'];
            $estudiante->persona_emergencia = $req->estudiante['persona_emergencia'];
            $estudiante->telefono_emergencia = $req->estudiante['telefono_emergencia'];
            $estudiante->apoderados = [
              'apoderado_titular' => $req->apoderado_titular,
              'apoderado_suplente' => empty($req->apoderado_suplente) ? null : $req->apoderado_suplente,
              'madre' => empty($req->madre) ? null : $req->madre,
              'padre' => empty($req->padre) ? null : $req->padre,
              'suplentes' => $req->suplentes
            ];

            $estudiante->save();

            return ['status' => 200, 'message' => 'Estudiante creado con exito!'];
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

    public function actualizar($id, $request)
    {
        $hasApoderado = !empty($request->apoderado_titular);
        $hasApoderadoSuplente = !empty($request->apoderado_suplente);
        $hasMadre = !empty($request->madre);
        $hasPadre = !empty($request->padre);       

        $request->validate(
            $this->rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre, $id),
            $this->messages(),
            $this->attributes()
        );

        try {
            $estudiante = Estudiante::findOrFail($id);
            Rut::parse($request->estudiante['run'])->validate();
            $rut = Rut::parse($request->estudiante['run'])->format(Rut::FORMAT_ESCAPED);
            $rut = Rut::parse($rut)->toArray();

            $estudiante->apellidos = $request->estudiante['apellido_paterno'] . ' ' . $request->estudiante['apellido_materno'];
            $estudiante->nombres = $request->estudiante['nombres'];
            $estudiante->rut = $rut[0];
            $estudiante->dv = $rut[1];
            $estudiante->prioridad = $request->estudiante['prioridad'];
            if ($estudiante->prioridad == 'prioritario') $estudiante->beca()->dissociate();
            $estudiante->curso_id = $request->estudiante['nivel'];
            $estudiante->edad = $request->estudiante['edad'];
            $estudiante->genero = $request->estudiante['genero'];
            $estudiante->nacionalidad = $request->estudiante['nacionalidad'];
            $estudiante->direccion = $request->estudiante['direccion'];
            $estudiante->telefono = $request->estudiante['telefono'];
            $estudiante->fecha_nacimiento = $request->estudiante['fecha_nacimiento'];
            $estudiante->email = $request->estudiante['email'];
            $estudiante->enfermedades = $request->estudiante['enfermedades'];
            $estudiante->persona_emergencia = $request->estudiante['persona_emergencia'];
            $estudiante->telefono_emergencia = $request->estudiante['telefono_emergencia'];
            $estudiante->apoderados = [
              'apoderado_titular' => $request->apoderado_titular,
              'apoderado_suplente' => empty($request->apoderado_suplente) ? null : $request->apoderado_suplente,
              'madre' => empty($request->madre) ? null : $request->madre,
              'padre' => empty($request->padre) ? null : $request->padre,
              'suplentes' => $request->suplentes
            ];

            $estudiante->save();
            return ['status' => 200, 'message' => 'Estudiante actualizado con exito!'];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => $e->getMessage()];
        }
    }

    public function apoderadoRemove($id, $apoderado) {
        try {
            Estudiante::findOrFail($id)->apoderados()->detach($apoderado);
            return redirect()->back()->with('res', ['status' => 200, 'message' => 'Apoderado removido del estudiante con exito']);
        } catch(Exception $e) {
            return redirect()->back()->with('res', ['status' => 400, 'message' => 'No se pudo remover al apoderado']);
        }
    }

    public function storePago($id, $req)
    {
        $estudiante = Estudiante::find($id);

        if(!$estudiante) return ['status' => 400, 'message' => 'No se encontro al estudiante'];
        if($estudiante->prioridad == 'prioritario') return ['status' => 400, 'message' => 'Los estudiantes prioritarios no deben pagar'];

        $esRecibo = $req->documento === 'recibo';

        $pago = new Pago;
        $minPago = $esRecibo ? $req->total : 1;
        $maxPago =  $estudiante->totalAPagar($req->anio, $req->mes, $req->total);
        
        if($maxPago <= 0) return ['status' => 400, 'message' => 'Este mas ya ha sido pagado completamente'];

        $req->validate(
            $pago->rules($req->num_documento, $maxPago, $minPago),
            $pago->messages($req->mes, $maxPago, $esRecibo),
            $pago->attributes()
        );
        
        try {
            $estudiante->pagos()->create($req->all());
            return ['status' => 200, 'message' => 'Pago registrado con éxito'];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => 'Ha ocurrido un error'];
        }
    }

    public function becaUpdate($id, $req)
    {
        try {
            $estudiante = Estudiante::find($id);
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
            Estudiante::find($id)->beca()->dissociate()->save();
            return ['status' => 200, 'message' => 'Beca removida con éxito'];
        } catch (Exception $e) {
            return ['status' => 400, 'message' => 'Ha ocurrido un error'];
        }
    }

    public function pagosPorAnio($anio)
    {
        $pagos_anio = $this->pagos()->where('anio', $anio)->oldest()->get();

        return [
            'matricula' => $pagos_anio->where('mes', 'matricula'),
            'marzo' => $pagos_anio->where('mes', 'marzo'),
            'abril' => $pagos_anio->where('mes', 'abril'),
            'mayo' => $pagos_anio->where('mes', 'mayo'),
            'junio' => $pagos_anio->where('mes', 'junio'),
            'julio' => $pagos_anio->where('mes', 'julio'),
            'agosto' => $pagos_anio->where('mes', 'agosto'),
            'septiembre' => $pagos_anio->where('mes', 'septiembre'),
            'octubre' => $pagos_anio->where('mes', 'octubre'),
            'noviembre' => $pagos_anio->where('mes', 'noviembre'),
            'diciembre' => $pagos_anio->where('mes', 'diciembre')
        ];
    }

    public function mesFaltante($pagos, $totalAPagar)
    {
        foreach ($pagos as $mes => $pagosMes) {
            if ($mes != 'matricula') {
                if (count($pagosMes) == 0)
                    return $mes;

                $total = 0;
                foreach ($pagosMes as $pago)
                    $total += $pago->valor;
                if ($total < $totalAPagar)
                    return $mes;
            }
        }
    }

    public function pagosMes($year, $month) {
        return $this->pagos()->where(['anio' => $year, 'mes' => $month])->get();
    }

    public function totalPagadoMes($pagosMes) {
        $total = 0;
            foreach ($pagosMes as $pago)
                $total += $pago->valor;

        return $total;
    }

    /**
     *   @param  string $year  --> año del pago
     *   @param  string $month --> mes del pago
     *   @param  integer $tAp  --> total a pagar
     *   @return integer       --> total que falta pagar en ese mes
    */
    public function totalAPagar($year, $month, $tAP) {
        if($month == 'matricula')
            return $this->totalAPagarMatricula($year);

        return $tAP - $this->totalPagadoMes($this->pagosMes($year, $month));
    }

    public function costoMatricula() {
        $total = $this->curso->nivel->matricula;
        $descuentos = $this->getDescuentos();

        if($descuentos >= 100)
          return 0;
        
        return $total * (1 - number_format('0.'. $descuentos, 2));
    }

    public function totalAPagarMatricula($year) {
        $tAP = $this->costoMatricula();

        return $tAP - $this->totalPagadoMes($this->pagosMes($year, 'matricula'));
    }

    public function poseeRun()
    {
        if ($this->rut)
            return 1;
        return 2;
    }

    /**
     *  Retorna la cantidad de mensualidades un estudiante ha pagado en X año
     *  @return integer
    */
    public function mesesPagados($anio)
    {
        $cantidad = 0;

        foreach ($this->pagosPorAnio($anio) as $mes => $pagosMes) {
            if ($mes != 'matricula') {
                if (count($pagosMes) == 0) continue;
                
                $total = $this->totalPagadoMes($pagosMes);
                
                if ($total == $this->curso->nivel->arancel)
                    $cantidad++;
            }
        }

        return $cantidad;
    }

    // La suma total de los pagos realizados dentro de X año
    public function totalMensualidades($anio)
    {
        $total = 0;

        foreach ($this->pagosPorAnio($anio) as $mes => $pagosMes)
            if ($mes != 'matricula')
                $total += $this->totalPagadoMes($pagosMes);

        return $total;
    }

    // Meses que el estudiante no ha pagado dentro de X año
    public function mesesPorPagar($anio)
    {
        $meses = [];
        foreach ($this->pagosPorAnio($anio) as $mes => $pagosMes) {
            $total = $this->totalPagadoMes($pagosMes);

            if ($total < $this->curso->nivel->arancel)
                array_push($meses, ['mes' => $mes, 'pagado' => $total, 'falta' => $this->curso->nivel->arancel - $total]);
        }

        return $meses;
    }

    public function getTotalMensualidadesBoleta($year = 0) {
        if(!$year) $year = now()->year;

        $total = 0;
        $pagos = $this->pagos()->year($year)->boleta()->get();

        foreach($pagos as $pago) {
            $total += $pago->valor;
        }

        return $total;
    }

    public function getTotalPagos($year = 0) {
        if(!$year) $year = now()->year;

        $total = 0;
        $pagos = $this->pagos()->year($year)->get();

        foreach($pagos as $pago) {
            $total += $pago->valor;
        }

        return $total;
    }
    
    public function registrosFicom($year)
    {
        return [
            'RBD' => env('RBD', 14901),
            'Posee RUN' => $this->poseeRun(),
            'RUN alumno' => $this->rut,
            'DV alumno' => $this->dv,
            'Annio mensualidad percibida' => $year,
            'Monto total mensualidad' => $this->getTotalMensualidadesBoleta($year),
            'Monto total intereses y/o gastos de cobranza' => 0,
            'Cantidad de mensualidades' => $this->pagos()->year($year)->boleta()->count(),
            'Tipo de Documento' => env('TIPO_DOCUMENTO', 'BOLEX')
        ];
    }

    public function getMes($mes) {
        $meses = [
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre',
        ];

        return $meses[$mes];
    }

    public function getArancel() {
        return $this->curso->nivel->arancel;
    }

    /**
     * getTotalAPagarPorMes
     * @var Integer $descuentos -> porcentaje de descuento que tiene el estudiante
     * @return Integer cuanto tiene que pagar por mes
    */
    public function getTotalAPagarPorMes() {
        $total = $this->getArancel();
        $descuentos = $this->getDescuentos();

        if($descuentos >= 100) 
            return 0;

        return $total * (1 - number_format('0.'. $descuentos, 2));
    }

    public function getDescuentos() {
        $descuentos = 0;
        $beca = $this->beca;

        if($this->prioridad == 'prioritario')
            return 100;

        if($this->prioridad == 'nuevo prioritario')
            $descuentos += env('DESCUENTO_NUEVO_PRIORITARIO');

        if($beca)
            $descuentos += $beca->descuento;

        return $descuentos;
    }

    public function getTotalAPagarMes($mes) {
        $totalAPagar = $this->getTotalAPagarPorMes();
        $totalPagado = $this->totalPagadoMes($this->pagosMes(date('y'), $mes));

        return $totalAPagar - $totalPagado;
    }

    public function recordatorioDePago($id) {
        try {
            $estudiante = Estudiante::findOrFail($id);
    
            $totalAPagar = $estudiante->getTotalAPagarPorMes();
            
            if($totalAPagar == 0) 
                return false;
            
            $mes = $this->getMes(date('m'));
            $apoderado = $estudiante->getApoderado();
    
            if(!$apoderado) 
                return false;
    
            $datosPago = [
                'mes' => $mes,
                'arancel' => $estudiante->curso->nivel->arancel,
                'totalDescuentos' => $estudiante->getDescuentos(),
                'abonado' => $estudiante->totalPagadoMes($estudiante->pagosMes(date('y'), $mes)),
                'totalPagar' => $estudiante->getTotalAPagarPorMes()
            ];
    
            Mail::mailer("smtp")->to($apoderado->email)->send(new RecordatorioPago($estudiante, $apoderado, $datosPago));
    
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getApoderado() {
        return $this->apoderadoTitular()->first() ?? $this->apoderadoSuplente()->first();
    }

    public function montoAnual() {
        $totalXMes = $this->getTotalAPagarPorMes();
        
        if($totalXMes == 0)
          return 0;

        return ($totalXMes * 12) + $this->costoMatricula();
    }
}