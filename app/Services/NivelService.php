<?php
namespace App\Services;

use App\Models\Nivel;
use Exception;

class NivelService
{
    protected $nivel;

    public function __construct(Nivel $nivel) {
        $this->nivel = $nivel;
    }

    public function actualizar($id, $req) {
        $validated = $req->validated();
        try {
            $this->nivel::findOrFail($id)->update(['nombre' => $validated['nombre']]);
            return ['status' => 200, 'message' => 'Nivel actualizado con éxito']; 
        } catch(Exception $e) {
            return ['status' => 400, 'message' => 'No se pudo actualizar el nivel', 'nivel' => $req->except('_token')];
        }
    }
}