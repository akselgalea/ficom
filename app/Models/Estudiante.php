<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Freshwork\ChileanBundle\Exceptions\InvalidFormatException;
use Freshwork\ChileanBundle\Rut;
use Exception;
use Illuminate\Validation\Rule;
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

    static function rules($hasApoderado, $hasApoderadoSuplente, $hasMadre, $hasPadre, $id = null): array {
        return [
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
    }

    static function messages(): array {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'email_institucional.unique' => 'Este correo ya esta en uso',
            'min' => 'El campo :attribute requiere un minimo de :min caracteres',
            'max' => 'El campo :attribute requiere un maximo de :max caracteres'
        ];
    }

    static function attributes(): array {
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

    public function pagosPorAnio($anio)
    {
        $pagos_anio = $this->pagos()->where('anio', $anio)->get();

        $meses = [
            'matricula' => [],
            'marzo' => [],
            'abril' => [],
            'mayo' => [],
            'junio' => [],
            'julio' => [],
            'agosto' => [],
            'septiembre' => [],
            'octubre' => [],
            'noviembre' => [],
            'diciembre' => []
        ];

        foreach($pagos_anio as $pago) {
            array_push($meses[$pago['mes']], $pago);
        }

        return $meses;
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

    public function totalPagadoMes($year, $month) {
        $pagos_mes = $this->pagos()
            ->selectRaw('SUM(valor) as totalPagado')
            ->where(['anio' => $year, 'mes' => $month])
            ->groupBy('mes')
            ->first();

        return $pagos_mes['totalPagado'];
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

        return $tAP - $this->totalPagadoMes($year, $month);
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

        return $tAP - $this->totalPagadoMes($year, 'matricula');
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

    public function getArancel() {
        return $this->curso->nivel->arancel;
    }

    /**
     * getTotalAPagarPorMes
     * @var Integer $descuentos -> porcentaje de descuento que tiene el estudiante
     * @return Integer cuanto tiene que pagar por mes
    */
    public function getTotalAPagarPorMes() {
        $total = $this->curso->nivel->mensualidad;
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

    public function montoAnual() {
        $totalXMes = $this->getTotalAPagarPorMes();
        
        if($totalXMes == 0)
          return 0;

        return ($totalXMes * 12) + $this->costoMatricula();
    }
}