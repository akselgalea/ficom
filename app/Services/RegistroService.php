<?php
namespace App\Services;

use App\Models\Registro;
use Exception;

class RegistroService
{
    protected $registro;

    public function __construct() {
        $this->registro = new Registro;
    }

    static function store($path, $method, $data, $response, $user) {
      $status = $response->status();

      Registro::create([
        'path' => $path,
        'method' => $method,
        'data' => $data,
        'status' => $response->status(),
        'user_id' => $user->id
      ]);
    }

    public function get($req) {
      $query = $req->query('action');

      if(!empty($query)) {
        try {
          return Registro::action($query)->with('userAndRole')->get();
        } catch(Exception $e) {
          return ['status' => 404, 'message' => 'Acción no encontrada'];
        }
      }

      return Registro::with('userAndRole')->get();
    }
}