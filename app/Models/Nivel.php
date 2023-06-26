<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $fillable = [
        'nombre',
        'matricula',
        'arancel'
    ];


    /**
     * Get all of the cursos for the Nivel
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos(): HasMany {
        return $this->hasMany(Curso::class);
    }
}
