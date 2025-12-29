<?php

namespace App\Http\Livewire;

use App\Models\Emision;
use Livewire\Component;
use App\Models\Registro;

class Registros extends Component
{
    public function render()
    {
        $registros = Emision::orderBy('fecha', 'desc')->get();

        return view('livewire.registros', compact('registros'));
    }
}
