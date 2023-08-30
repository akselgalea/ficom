<?php
namespace App\Services;

use App\Models\User;
use Exception;

class UserService
{
    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index($req) {
      $query = $req->query('q');
      if($query) {
        if($query === 'eliminados')
          return User::with('roles')->onlyTrashed()->get();

        try {
          return User::role($query)->with('roles')->get();
        } catch(Exception $e) {
          return ['status' => 404, 'message' => 'rol no encontrado'];
        }
      }

      return User::with('roles')->get();
    }

    public function get($id) {
      return $this->user->findOrFail($id);
    }

    public function store($req) {
      $validated = $req->validated();
      $this->user->create($validated);
      
      return ['status' => 200, 'message' => 'Usuario creado con éxito!'];
    }

    public function update($id, $req) {
      $validated = $req->validated();

      try {
        $user = $this->user::with('roles')->findOrFail($id);
        $user->update([
          'name' => $validated['name'],
          'email' => $validated['email']
        ]);

        $user->syncRoles($validated['roles']);

        return ['status' => 200, 'message' => 'Usuario actualizado con éxito', 'user' => $user]; 
      } catch(Exception $e) {
        return ['status' => 400, 'message' => 'No se pudo actualizar al usuario', 'user' => $req->except('_token')];
      }
    }

    public function delete($id) {
      try {
        $this->user->findOrFail($id)->delete();
        return ['status' => 200, 'message' => 'Usuario eliminado con éxito']; 
      } catch(Exception $e) {
        return ['status' => 400, 'message' => 'No se pudo eliminar al usuario'];
      }
    }
}