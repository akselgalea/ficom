<?php
namespace App\Services;

use App\Models\{Estudiante, Pago};
use Exception;

class PagoService
{
    protected $estudiante;

    public function __construct() {
        $this->estudiante = new Estudiante;
    }

    function findById($id) {
      return Pago::findOrFail($id);
    }

    function recordatorioMensual() {
      try {
        $estudiantes = $this->estudiante->all();
        
        foreach($estudiantes as $e) {
          $this->estudiante->recordatorioDePago($e->id);
        }

      } catch(Exception $error) {
        return $error->getMessage(); 
      }
    }

    function actualizar($id, $req) {
        $validated = $req->validated();
        try {
            $this->nivel::findOrFail($id)->update($validated);
            return ['status' => 200, 'message' => 'Nivel actualizado con éxito']; 
        } catch(Exception $e) {
            return ['status' => 400, 'message' => 'No se pudo actualizar el nivel', 'nivel' => $req->except('_token')];
        }
    }

    function delete($id) {
      try {
        $this->findById($id)->delete();

        return ['message' => 'Pago eliminado con éxito'];
      } catch (Exception $e) {
        return ['status' => 500, 'message' => 'No se pudo eliminar el pago'];
      }
    }
}