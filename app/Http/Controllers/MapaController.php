<?php

namespace App\Http\Controllers;

use App\Models\Emi0725;
use App\Models\Registros;
use Illuminate\Http\Request;

class MapaController extends Controller
{
    //
    public function index()
    {
        // Obtenemos solo direcciones para el mapa
        $direcciones = Registros::pluck('direccion')->toArray();

        return view('mapa.registros', compact('direcciones'));
    }
    
}
