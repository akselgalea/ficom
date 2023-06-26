<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nivel;
use App\Services\NivelService;
use App\Http\Requests\UpdateNivelRequest;

class NivelController extends Controller
{
    private $nivel;
    private $ns;

    public function __construct(Nivel $nivel, NivelService $ns) {
        $this->nivel = $nivel;
        $this->ns = $ns;
    }
    
    public function index()
    {
        return view('niveles.index', ['niveles' => $this->nivel::all()]);
    }

    public function cursos($id)
    {
        return view('curso.index', ['cursos' => $this->nivel::findOrFail($id)->cursos, 'niveles' => $this->nivel->all()]);
    }

    public function edit($id)
    {
        return view('niveles.editar', ['nivel' => $this->nivel::findOrFail($id)]);
    }

    public function update($id, UpdateNivelRequest $req)
    {
        $res = $this->ns->actualizar($id, $req);
        return redirect()->back()->with('res', $res);
    }
}
