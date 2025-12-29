<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VisualizarAdm extends Component
{
    public $filtro_id, $fechad, $arqueoant;
    public function render()
    {
        return view('livewire.visualizar-adm');
    }
}
