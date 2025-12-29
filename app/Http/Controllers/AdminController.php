<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Emision;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function geocodificarYGuardar()
    {
        $registro = Emision::where('documento', 2443543)->first();

        // Armar texto de búsqueda
        $direccionCompleta = $registro->dir_cli . ', ' . $registro->loc_cli . ', Canelones, Uruguay';

        // Llamada a Nominatim
        $url = 'https://nominatim.openstreetmap.org/search';

        $response = Http::get($url, [
            'q' => $direccionCompleta,
            'format' => 'json',
            'limit' => 1
        ]);

        if ($response->successful() && count($response->json()) > 0) {
            $data = $response->json()[0];

            $registro->latitud = $data['lat'];
            $registro->longitud = $data['lon'];
            $registro->save();

            return "Latitud y longitud guardadas correctamente." . $direccionCompleta;
        }

        return "No se pudo obtener coordenadas." . $direccionCompleta;
    }

    public function geocodeIdeUy()
    {
        $registro = Emision::where('documento', 102940)->first();

        $calle = $registro->dir_cli;
        $departamento = "Canelones";
        $localidad = $registro->zona;

        $url = 'https://direcciones.ide.uy/api/v0/geocode/BusquedaDireccion';

        $response = Http::get($url, [
            'calle' => $calle,
            'departamento' => $departamento,
            'localidad' => $localidad,
        ]);

        if (!$response->successful()) {
            return "Error consultando IDE uy";
        }

        $data = $response->json();

        // La API devuelve un array de coincidencias
        if (count($data) === 0) {
            return "No se encontraron resultados para esta dirección." . $calle . $departamento . $localidad;
        }

        // Tomamos el primer resultado
        $resultado = $data[0];

        $lat = $resultado['puntoY'];
        $lon = $resultado['puntoX'];

        // Guardar en la BD
        $registro->latitud = $lat;
        $registro->longitud = $lon;
        $registro->save();

        return 'Coordenadas guardadas:' .  $lat . ' y ' . $lon;
    }

    public function geocodeTodos()
    {
        $registro = Emision::whereNull('latitud')->where('documento', '>', 2333697)->where('documento', '<', 2334080)->get();
        $cantidad = 0;
        foreach ($registro as $registross) {
            $guardar = Emision::where('nro', $registross->nro)->first();
            $calle = $guardar->dir_cli;
            $departamento = "Canelones";
            $localidad = $guardar->zona;

            $url = 'https://direcciones.ide.uy/api/v0/geocode/BusquedaDireccion';

            $response = Http::get($url, [
                'calle' => $calle,
                'departamento' => $departamento,
                'localidad' => $localidad,
            ]);

            $data = $response->json();
            if (count($data) === 0) {
                $cantidad++;
            } else {
                $resultado = $data[0];

                $lat = $resultado['puntoY'];
                $lon = $resultado['puntoX'];

                // Guardar en la BD
                $guardar->latitud = $lat;
                $guardar->longitud = $lon;
                $guardar->save();
                $cantidad++;
            }

            // La API devuelve un array de coincidencias
            //            if (count($data) === 0) {
            //                return "No se encontraron resultados para esta dirección." . $calle . $departamento . $localidad;
            //            }

            // Tomamos el primer resultado
        }

        return 'Coordenadas guardadas:' .  $cantidad;
    }
}
