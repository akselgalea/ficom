<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};
use Exception;
use Illuminate\Validation\Rule;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'curso',
        'paralelo',
        'nivel_id'
    ];
    /**
     * Get all of the estudiantes for the Curso
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class);
    }

    public function nivel(): BelongsTo 
    {
        return $this->belongsTo(Nivel::class);
    }

    public function cantEstudiantes() {
        return $this->estudiantes()->count();
    }

    public function regulares() {
        return $this->estudiantes()->where('prioridad', 'alumno regular');
    }

    public function prioritarios() {
        return $this->estudiantes()->where('prioridad', 'prioritario');
    }

    public function nuevosPrioritarios() {
        return $this->estudiantes()->where('prioridad', 'nuevo prioritario');
    }

    public function actualizar($id, $req) {;
        try {
            Curso::find($id)->update($req->all());
            return ['status' => 200, 'message' => 'Curso actualizado con éxito']; 
        } catch(Exception $e) {
            return ['status' => 400, 'message' => 'No se pudo actualizar el curso', 'cursoErr' => $req->except('_token')];
        }
    }

    public function eliminar($id) {
        try {
            Curso::find($id)->delete();
            return ['status' => 200, 'message' => 'Curso eliminado con éxito']; 
        } catch(Exception $e) {
            return ['status' => 400, 'message' => 'No se pudo eliminar el curso'];
        }
    }

    public function informeMontoPendiente() {
      $estudiantes = $this->estudiantes;

      $res = [];

      foreach ($estudiantes as $e) {
        $monto_cancelado = 0;

        array_push($res, [
          "nombre_completo" => "$e->nombres $e->apellidos",
          "monto_anual" => $e->montoAnual(),
          "beca" => $e->beca->descuento,
          "excencion" => null,
          "prioritario" => $e->prioridad,
          "matricula" => $e->costoMatricula(),
          "abono" => 25000,
          "monto_cancelado" => $monto_cancelado,
          "monto_pendiente" => $e->montoAnual() - $monto_cancelado
        ]);
      }
      
      return $res;
    }
}
