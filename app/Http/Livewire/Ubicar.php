<?php

namespace App\Http\Livewire;

use App\Models\Emision;
use Livewire\Component;

class Ubicar extends Component
{
    public $mostrarMapa = false;
    public $registros;
    public $primeraUbicacion;
    public $registroSeleccionado = null;

    public function mount()
    {
        $this->cargarRegistros();
    }

    public function cargarRegistros()
    {
        $this->registros = Emision::whereNotNull('latitud')->orderBy('fecha', 'desc')->get();
        $this->primeraUbicacion = $this->getFirstLocation();
    }

    // app/Http/Livewire/RegistrosTable.php

    public function seleccionarRegistro($registroId)
    {
        $this->registroSeleccionado = $registroId;

        // Aquí puedes hacer lo que necesites: cargar detalles, emitir eventos, etc.
        $this->emit('registroSeleccionado', $registroId); // si usas @livewireScripts con Alpine
    }
    public function getFirstLocation()
    {
        $primerRegistro = Emision::whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->first();

        if ($primerRegistro) {
            return [
                'lat' => $primerRegistro->latitud,
                'lng' => $primerRegistro->longitud
            ];
        }

        // Coordenadas por defecto (Montevideo)
        return [
            'lat' => -34.9011,
            'lng' => -56.1645
        ];
    }

    public function toggleMapa()
    {
        $this->mostrarMapa = !$this->mostrarMapa;
    }

    public function render()
    {
        return view('livewire.ubicar');
    }
}
