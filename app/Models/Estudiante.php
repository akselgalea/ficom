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

class Estudiante extends Model
{
    use HasFactory;

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
        return [
            'nombres' => 'required|max:255',
            'apellidos' => 'sometimes|required|max:255',
            'apellido_paterno' => 'sometimes|required|max:255',
            'apellido_materno' => 'sometimes|required|max:255',
            'genero' => [Rule::in(['M', 'F', 'N']), 'required'],
            'edad' => 'required|int',
            'enfermedades' => 'nullable|string',
            'persona_emergencia' => 'nullable|string',
            'telefono_emergencia' => 'nullable|string',
            'nacionalidad' => 'required|string|min:3|max:50',
            'run' => 'required|max:10',
            'email' => ['required', 'email', Rule::unique('estudiantes')->ignore($id)],
            'email_institucional' => ['sometimes', 'nullable', 'email', Rule::unique('estudiantes')->ignore($id)],
            'nivel' => 'required',
            'prioridad' => 'required',
            'a_nombre' => Rule::requiredIf($hasApoderado),
            'a_rut' => Rule::requiredIf($hasApoderado),
            'a_telefono' => [Rule::requiredIf($hasApoderado), 'min:8', 'max:12', 'nullable'],
            'a_email' => [Rule::requiredIf($hasApoderado), 'email', 'nullable'],
            'a_direccion' => Rule::requiredIf($hasApoderado),
            'sub_nombre' => Rule::requiredIf($hasApoderadoSuplente),
            'sub_rut' => Rule::requiredIf($hasApoderadoSuplente),
            'sub_telefono' => [Rule::requiredIf($hasApoderadoSuplente), 'min:8', 'max:12', 'nullable'],
            'sub_email' => [Rule::requiredIf($hasApoderadoSuplente), 'email', 'nullable'],
            'sub_direccion' => Rule::requiredIf($hasApoderadoSuplente),
            'm_nombre' => Rule::requiredIf($hasMadre),
            'm_rut' => Rule::requiredIf($hasMadre),
            'm_telefono' => [Rule::requiredIf($hasMadre), 'min:8', 'max:12', 'nullable'],
            'm_email' => [Rule::requiredIf($hasMadre), 'email', 'nullable'],
            'm_direccion' => Rule::requiredIf($hasMadre),
            'p_nombre' => Rule::requiredIf($hasPadre),
            'p_rut' => Rule::requiredIf($hasPadre),
            'p_telefono' => [Rule::requiredIf($hasPadre), 'min:8', 'max:12', 'nullable'],
            'p_email' => [Rule::requiredIf($hasPadre), 'email', 'nullable'],
            'p_direccion' => Rule::requiredIf($hasPadre)
        ];
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
            'nombres',
            'apellidos',
            'apellido_paterno' => 'apellido paterno',
            'apellido_materno' => 'apellido materno',
            'run',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'enfermedades' => 'enfermedades y contraindicaciones',
            'persona_emergencia' => 'remitir a',
            'telefono_emergencia' => 'N° Telefónico',
            'genero' => 'género',
            'email_institucional' => 'correo institucional',
            'nivel',
            'prioridad',
            'a_nombre' => 'nombre',
            'a_rut' => 'rut',
            'a_telefono' => 'telefono',
            'a_email' => 'email',
            'a_direccion' => 'direccion',
            'sub_nombre' => 'nombre',
            'sub_rut' => 'rut',
            'sub_telefono' => 'telefono',
            'sub_email' => 'email',
            'sub_direccion' => 'direccion',
            'm_nombre' => 'nombre',
            'm_rut' => 'rut',
            'm_telefono' => 'telefono',
            'm_email' => 'email',
            'm_direccion' => 'direccion',
            'p_nombre' => 'nombre',
            'p_rut' => 'rut',
            'p_telefono' => 'telefono',
            'p_email' => 'email',
            'p_direccion' => 'direccion'
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
        $hasApoderado = $req->a_nombre != '' || $req->a_rut != '' || $req->a_telefono != '' || $req->a_email != '' || $req->a_direccion != '';
        $hasApoderadoSuplente = $req->sub_nombre != '' || $req->sub_rut != '' || $req->sub_telefono != '' || $req->sub_email != '' || $req->sub_direccion != '';
        $hasMadre = $req->m_nombre != '' || $req->m_rut != '' || $req->m_telefono != '' || $req->m_email != '' || $req->m_direccion != '';
        $hasPadre = $req->p_nombre != '' || $req->p_rut != '' || $req->p_telefono != '' || $req->p_email != '' || $req->p_direccion != '';
        
        $req->validate(
            $this->rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre),
            $this->messages(),
            $this->attributes()
        );

        try {
            Rut::parse($req->run)->validate();
            $rut = Rut::parse($req->run)->format(Rut::FORMAT_ESCAPED);
            $rut = Rut::parse($rut)->toArray();

            //Estudiante
            $estudiante = new Estudiante();
            $estudiante->nombres = $req->nombres;
            $estudiante->apellidos = $req->apellido_paterno . ' ' . $req->apellido_materno;
            $estudiante->rut = $rut[0];
            $estudiante->dv = $rut[1];
            $estudiante->es_nuevo = 1;
            $estudiante->edad = $req->edad;
            $estudiante->genero = $req->genero;
            $estudiante->direccion = $req->direccion;
            $estudiante->nacionalidad = $req->nacionalidad;
            $estudiante->telefono = $req->telefono;
            $estudiante->curso_id = $req->nivel;
            $estudiante->prioridad = $req->prioridad;
            $estudiante->fecha_nacimiento = $req->fecha_nacimiento;
            $estudiante->email = $req->email;
            $estudiante->enfermedades = $req->enfermedades;
            $estudiante->persona_emergencia = $req->persona_emergencia;
            $estudiante->telefono_emergencia = $req->telefono_emergencia;
            $estudiante->apoderados = [
              "apoderado_titular" => [
                "nombre" => $req->a_nombre,
                "rut" => $req->a_rut,
                "telefono" => $req->a_telefono,
                "email" => $req->a_email,
                "direccion" => $req->a_direccion
              ],
              "apoderado_suplente" => [
                "nombre" => $req->sub_nombre,
                "rut" => $req->sub_rut,
                "telefono" => $req->sub_telefono,
                "email" => $req->sub_email,
                "direccion" => $req->sub_direccion
              ],
              "madre" => [
                "nombre" => $req->m_nombre,
                "rut" => $req->m_rut,
                "telefono" => $req->m_telefono,
                "email" => $req->m_email,
                "direccion" => $req->m_direccion
              ],
              "padre" => [
                "nombre" => $req->p_nombre,
                "rut" => $req->p_rut,
                "telefono" => $req->p_telefono,
                "email" => $req->p_email,
                "direccion" => $req->p_direccion
              ]
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
        $hasApoderado = $request->a_nombre != '' || $request->a_rut != '' || $request->a_telefono != '' || $request->a_email != '' || $request->a_direccion != '';
        $hasApoderadoSuplente = $request->sub_nombre != '' || $request->sub_rut != '' || $request->sub_telefono != '' || $request->sub_email != '' || $request->sub_direccion != '';
        $hasMadre = $request->m_nombre != '' || $request->m_rut != '' || $request->m_telefono != '' || $request->m_email != '' || $request->m_direccion != '';
        $hasPadre = $request->p_nombre != '' || $request->p_rut != '' || $request->p_telefono != '' || $request->p_email != '' || $request->p_direccion != '';
        
        $request->validate(
            $this->rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre, $id),
            $this->messages(),
            $this->attributes()
        );

        try {
            $estudiante = Estudiante::findOrFail($id);
            Rut::parse($request->run)->validate();
            $rut = Rut::parse($request->run)->format(Rut::FORMAT_ESCAPED);
            $rut = Rut::parse($rut)->toArray();

            $estudiante->apellidos = $request->apellidos;
            $estudiante->nombres = $request->nombres;
            $estudiante->rut = $rut[0];
            $estudiante->dv = $rut[1];
            $estudiante->prioridad = $request->prioridad;
            if ($estudiante->prioridad == 'prioritario') $estudiante->beca()->dissociate();
            $estudiante->curso_id = $request->nivel;
            $estudiante->edad = $request->edad;
            $estudiante->genero = $request->genero;
            $estudiante->nacionalidad = $request->nacionalidad;
            $estudiante->direccion = $request->direccion;
            $estudiante->telefono = $request->telefono;
            $estudiante->fecha_nacimiento = $request->fecha_nacimiento;
            $estudiante->email = $request->email;
            $estudiante->enfermedades = $request->enfermedades;
            $estudiante->persona_emergencia = $request->persona_emergencia;
            $estudiante->telefono_emergencia = $request->telefono_emergencia;
            
            $estudiante->apoderados = [
              "apoderado_titular" => [
                "nombre" => $request->a_nombre,
                "rut" => $request->a_rut,
                "telefono" => $request->a_telefono,
                "email" => $request->a_email,
                "direccion" => $request->a_direccion
              ],
              "apoderado_suplente" => [
                "nombre" => $request->sub_nombre,
                "rut" => $request->sub_rut,
                "telefono" => $request->sub_telefono,
                "email" => $request->sub_email,
                "direccion" => $request->sub_direccion
              ],
              "madre" => [
                "nombre" => $request->m_nombre,
                "rut" => $request->m_rut,
                "telefono" => $request->m_telefono,
                "email" => $request->m_email,
                "direccion" => $request->m_direccion
              ],
              "padre" => [
                "nombre" => $request->p_nombre,
                "rut" => $request->p_rut,
                "telefono" => $request->p_telefono,
                "email" => $request->p_email,
                "direccion" => $request->p_direccion
              ]
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
        
        $pago = new Pago;
        $maxPago = $estudiante->totalAPagar($req->anio, $req->mes, $req->total);
        
        if($maxPago <= 0) return ['status' => 400, 'message' => 'Este mas ya ha sido pagado completamente'];

        $req->validate(
            $pago->rules($req->num_documento, $maxPago),
            $pago->messages($req->mes, $maxPago),
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