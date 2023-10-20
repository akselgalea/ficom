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

    function update($id, $req) {
      // if($maxPago <= 0) return ['status' => 400, 'message' => 'Este mas ya ha sido pagado completamente'];
      $validated = $req->validate(
        Pago::rules(0, 0, $req->id),
        Pago::messages($req->mes, 0, 0),
        Pago::attributes()
      );

      try {
        Pago::findOrFail($id)->update($validated);
        return ['status' => 200, 'message' => 'Pago actualizado con éxito']; 
      } catch(Exception $e) {
        return ['status' => 400, 'message' => 'No se pudo actualizar el pago', 'pago' => $req->except('_token')];
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