<?php
namespace App\Services;

use App\Models\Estudiante;
use Exception;

class PagoService
{
    protected $estudiante;

    public function __construct() {
        $this->estudiante = new Estudiante;
    }

    public function recordatorioMensual() {
      try {
        $estudiantes = $this->estudiante->all();
        
        foreach($estudiantes as $e) {
          $this->estudiante->recordatorioDePago($e->id);
        }

      } catch(Exception $error) {
        return $error->getMessage(); 
      }
    }

    public function actualizar($id, $req) {
        $validated = $req->validated();
        try {
            $this->nivel::findOrFail($id)->update($validated);
            return ['status' => 200, 'message' => 'Nivel actualizado con éxito']; 
        } catch(Exception $e) {
            return ['status' => 400, 'message' => 'No se pudo actualizar el nivel', 'nivel' => $req->except('_token')];
        }
    }
}