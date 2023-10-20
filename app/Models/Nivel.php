<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    use HasFactory;
    const MESES_A_PAGAR = 10;

    protected $table = 'niveles';

    protected $fillable = [
        'nombre'
    ];

    protected $appends = [
        'periodo_actual'
    ];

    /**
     * Get all of the cursos for the Nivel
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos(): HasMany {
        return $this->hasMany(Curso::class);
    }

    public function costos(): HasMany {
        return $this->hasMany(NivelCosto::class)->orderBy('periodo', 'desc');
    }

    public function costoPeriodoActual() {
        return $this->costos()->where('periodo', date('Y'))->first();
    }

    public function calcMensualidad() {
        $periodo = $this->costoPeriodoActual();
        return ($periodo->arancel - $periodo->matricula) / self::MESES_A_PAGAR;
    }

    public function calcMensualidadYear($year) {
        $periodo = $this->costos()->where('periodo', $year)->first();
        return ($periodo->arancel - $periodo->matricula) / self::MESES_A_PAGAR;
    }

    public function getPeriodoActualAttribute() {
        $periodo = $this->costoPeriodoActual();
        $periodo->mensualidad = ($periodo->arancel - $periodo->matricula) / self::MESES_A_PAGAR;
        
        return $periodo;
    }
}
