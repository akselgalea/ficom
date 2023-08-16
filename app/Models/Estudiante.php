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
        'nombres',
        'apellidos',
        'genero',
        'rut',
        'dv',
        'prioridad',
        'email_institucional',
        'telefono',
        'direccion',
        'es_nuevo',
        'curso_id',
        'beca_id'
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

    private function rules($hasApoderado, $hasApoderadoSuplente, $id = null): array {
        return [
            'nombres' => 'required|max:255',
            'apellidos' => 'sometimes|required|max:255',
            'apellido_paterno' => 'sometimes|required|max:255',
            'apellido_materno' => 'sometimes|required|max:255',
            'run' => 'required|max:10',
            'email_institucional' => ['sometimes', 'nullable', 'email', Rule::unique('estudiantes')->ignore($id)],
            'nivel' => 'required',
            'prioridad' => 'required',
            'a_nombres' => Rule::requiredIf($hasApoderado),
            'a_apellidos' => Rule::requiredIf($hasApoderado),
            'a_telefono' => [Rule::requiredIf($hasApoderado), 'min:8', 'max:12', 'nullable'],
            'a_email' => [Rule::requiredIf($hasApoderado), 'email', 'nullable'],
            'a_direccion' => Rule::requiredIf($hasApoderado),
            'sub_nombres' => Rule::requiredIf($hasApoderadoSuplente),
            'sub_apellidos' => Rule::requiredIf($hasApoderadoSuplente),
            'sub_telefono' => [Rule::requiredIf($hasApoderadoSuplente), 'min:8', 'max:12', 'nullable'],
            'sub_email' => [Rule::requiredIf($hasApoderadoSuplente), 'email', 'nullable'],
            'sub_direccion' => Rule::requiredIf($hasApoderadoSuplente)
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
            'email_institucional' => 'correo institucional',
            'nivel',
            'prioridad',
            'a_nombres' => 'nombres',
            'a_apellidos' => 'apellidos',
            'a_telefono' => 'telefono',
            'a_email' => 'email',
            'a_direccion' => 'direccion',
            'sub_nombres' => 'nombres',
            'sub_apellidos' => 'apellidos',
            'sub_telefono' => 'telefono',
            'sub_email' => 'email',
            'sub_direccion' => 'direccion'
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
        $hasApoderado = $req->a_nombres != '' || $req->a_apellidos != '' || $req->a_telefono != '' || $req->a_email != '' || $req->a_direccion != '';
        $hasApoderadoSuplente = $req->sub_nombres != '' || $req->sub_apellidos != '' || $req->sub_telefono != '' || $req->sub_email != '' || $req->sub_direccion != '';

        $req->validate(
            $this->rules($hasApoderado, $hasApoderadoSuplente),
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
            $estudiante->direccion = $req->direccion;
            $estudiante->telefono = $req->telefono;
            $estudiante->curso_id = $req->nivel;
            $estudiante->prioridad = $req->prioridad;
            $estudiante->save();

            //Apoderado
            if ($hasApoderado) {
                $apoderado = new Apoderado();
                $apoderado->apellidos = $req->a_apellidos;
                $apoderado->nombres = $req->a_nombres;
                $apoderado->telefono = $req->a_telefono;
                $apoderado->email = $req->a_email;
                $apoderado->direccion = $req->a_direccion;

                $estudiante->apoderados()->save($apoderado);
            }

            //Apoderado suplente
            if ($hasApoderadoSuplente) {
                $apoderado_sub = new Apoderado();
                $apoderado_sub->apellidos = $req->sub_apellidos;
                $apoderado_sub->nombres = $req->sub_nombres;
                $apoderado_sub->telefono = $req->sub_telefono;
                $apoderado_sub->email = $req->sub_email;
                $apoderado_sub->direccion = $req->sub_direccion;
                $estudiante->apoderados()->save($apoderado_sub, ['es_suplente' => true]);
            }

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
        $hasApoderado = $request->a_nombres != '' || $request->a_apellidos != '' || $request->a_telefono != '' || $request->a_email != '' || $request->a_direccion != '';
        $hasApoderadoSuplente = $request->sub_nombres != '' || $request->sub_apellidos != '' || $request->sub_telefono != '' || $request->sub_email != '' || $request->sub_direccion != '';

        $request->validate(
            $this->rules($hasApoderado, $hasApoderadoSuplente, $id),
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
            $estudiante->email_institucional = $request->email_institucional;
            $estudiante->prioridad = $request->prioridad;
            if ($estudiante->prioridad == 'prioritario') $estudiante->beca()->dissociate();
            $estudiante->curso_id = $request->nivel;
            $estudiante->telefono = $request->a_telefono;
            $estudiante->direccion = $request->a_direccion;
            
            if($hasApoderado) {
                if(!$estudiante->hasApoderadoTitular()) {
                    $apoderado = new Apoderado();
                    $apoderado->nombres = $request->a_nombres;
                    $apoderado->apellidos = $request->a_apellidos;
                    $apoderado->telefono = $request->a_telefono;
                    $apoderado->email = $request->a_email;
                    $apoderado->direccion = $request->a_direccion;
                    $estudiante->apoderados()->save($apoderado);
                } else {
                    $estudiante->apoderadoTitular()->update([
                        'apellidos' => $request->a_apellidos,
                        'nombres' => $request->a_nombres,
                        'telefono' => $request->a_telefono,
                        'email' => $request->a_email,
                        'direccion' => $request->a_direccion,
                    ]);
                }
            }

            if($hasApoderadoSuplente) {
                if (!$estudiante->hasApoderadoSuplente()) {
                    $apoderado = new Apoderado();
                    $apoderado->apellidos = $request->sub_apellidos;
                    $apoderado->nombres = $request->sub_nombres;
                    $apoderado->telefono = $request->sub_telefono;
                    $apoderado->email = $request->sub_email;
                    $apoderado->direccion = $request->sub_direccion;
                    $estudiante->apoderados()->save($apoderado, ['es_suplente' => true]);
                } else {
                    $estudiante->apoderadoSuplente()->update([
                        'apellidos' => $request->sub_apellidos,
                        'nombres' => $request->sub_nombres,
                        'telefono' => $request->sub_telefono,
                        'email' => $request->sub_email,
                        'direccion' => $request->sub_direccion,
                    ]);
                }
            }

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

    public function getTotalMensualidadesBoleta($year) {
        $total = 0;
        $pagos = $this->pagos()->year($year)->boleta()->get();

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