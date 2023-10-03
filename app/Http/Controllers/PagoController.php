<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PagoService;

class PagoController extends Controller
{
    private $pagoService;

    public function __construct(PagoService $pagoService) {
        $this->pagoService = $pagoService;
    }

    function delete($id, Request $req) {
        $result = $this->pagoService->delete($id, $req);

        return response()->json($result, $result['status'] ?? 200);
    }
}
