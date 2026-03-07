<?php

namespace App\Http\Livewire;

use App\Models\Abm_arqueo;
use Livewire\Component;

class Auditoria extends Component
{
    public $nrorec;
    public $mensajeExito;
    public $mensajeError;
    public $registros;

    public function mount()
    {
        $this->nrorec = '';
        $this->registros = [];
    }

    public function render()
    {
        return view('livewire.auditoria');
    }

    public function consultarRecibo()
    {
        if (empty($this->nrorec)) {
            $this->mensajeError = "Por favor, ingrese un número de recibo.";
            $this->registros = [];
            return;
        }
        $this->registros = Abm_arqueo::where('documento', $this->nrorec)->orderBy('fecha', 'desc')->orderBy('hora', 'desc')->get();
    }
}
