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
        'nombre',
        'matricula',
        'arancel'
    ];

    protected $appends = [
        'mensualidad'
    ];


    /**
     * Get all of the cursos for the Nivel
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos(): HasMany {
        return $this->hasMany(Curso::class);
    }

    public function calcMensualidad() {
        return ($this->arancel - $this->matricula) / self::MESES_A_PAGAR;
    }

    public function getMensualidadAttribute() {
        return $this->calcMensualidad();
    }
}
