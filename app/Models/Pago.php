<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $fillable = [
        'id',
        'mes',
        'anio',
        'documento',
        'num_documento',
        'fecha_pago',
        'valor',
        'forma',
        'observacion',
        'estudiante_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'anio' => 'integer'
    ];

    /**
     * Get the estudiante that owns the Pago
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    static function rules($maxPago, $minPago, $id = null): array {
        return [
            'mes' => 'required',
            'anio' => 'required',
            'documento' => 'required',
            'num_documento' => ['required', Rule::unique('pagos')->ignore($id)],
            'fecha_pago' => 'required',
            'valor' => $id ? "required|numeric" : "required|numeric|max:$maxPago|min:$minPago",
            'forma' => 'required',
            'observacion' => 'max:65000'
        ];
    }

    static function messages($mes, $maxPago, $esRecibo): array {
        return [
            'num_documento.unique' => 'Este documento ya se encuentra registrado',
            'required' => 'El campo :attribute es obligatorio',
            'min' => 'El campo :attribute requiere un minimo de :min caracteres',
            'valor.min' => $esRecibo ? 'Seleccione el tipo boleta si desea hacer un pago parcial' : "El pago debe ser mayor a 0 pesos",
            'max' => 'El campo :attribute requiere un maximo de :max caracteres',
            'valor.max' => "El monto que ingresaste excede el monto a pagar $maxPago en el mes de $mes"
        ];
    }

    static function attributes(): array {
        return [
            'mes',
            'anio' => 'año',
            'documento',
            'num_documento' => 'numero de documento',
            'fecha_pago' => 'fecha de pago',
            'valor',
            'forma',
            'observacion'
        ];
    }

    public function scopeBoleta(Builder $query): void
    {
        $query->where('documento', '=', 'boleta');
    }

    public function scopeYear(Builder $query, $year): void
    {
        $query->where('anio', '=', $year);
    }
}
