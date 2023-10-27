<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstudiantePeriodo extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'estudiante_periodo';
    protected $fillable = [
        'periodo',
        'prioridad',
        'estudiante_id',
        'curso_id',
        'beca_id'
    ];

    public function estudiante() {
        return $this->belongsTo(Estudiante::class);
    }

    public function curso() {
        return $this->belongsTo(Curso::class);
    }

    public function beca() {
        return $this->belongsTo(Beca::class);
    }
}
