<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\{UserService, RegistroService};
use Spatie\Permission\Models\Role;
use App\Http\Requests\{CreateUserRequest};
use Inertia\Inertia;

class UserController extends Controller
{
    private $user;
    private $reg;

    public function __construct(UserService $user, RegistroService $reg) {
      $this->user = $user;
      $this->reg = $reg;
    }

    public function index(Request $req) {
      Inertia::setRootView('layouts.inertia');
      $res = $this->user->index($req);

      if(!empty($res['status']))
        return redirect()->route('usuario.index')->with($res);

      return Inertia::render('Usuarios/Index', ['users' => $res, 'roles' => Role::all()->toArray()]);
    }

    public function get($id) {
      Inertia::setRootView('layouts.inertia');
      return Inertia::render('Usuarios/Perfil', ['users' => $this->user->get($id)]);
    }

    public function create() {
      Inertia::setRootView('layouts.inertia');
      return Inertia::render('Usuarios/Nuevo', ['roles' => Role::all()->toArray()]);
    }
    
    public function store(CreateUserRequest $req) {
      $res = $this->user->store($req);
      return response()->json($res, $res['status']);
    }

    public function update($id, CreateUserRequest $req) {
      $res = $this->user->update($id, $req);
      return response()->json($res, $res['status']);
    }

    public function delete($id) {
      $res = $this->user->delete($id);
      return response()->json($res, $res['status']);
    }

    public function bitacora(Request $req) {
      Inertia::setRootView('layouts.inertia');

      $res = $this->reg->get($req);
      
      if(!empty($res['status']))
        return redirect()->route('usuario.bitacora')->with($res);

      return Inertia::render('Usuarios/Bitacora', ['registros' => $res]);
    }

    public function checkPassword(Request $req) {
      $res = $this->user->checkPassword($req);
      return response()->json($res, $res['status'] ?? 200);
    }
}
