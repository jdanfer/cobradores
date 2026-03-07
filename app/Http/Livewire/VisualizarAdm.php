<?php

namespace App\Http\Livewire;

use App\Models\Cob_entrega;
use App\Models\Emision;
use App\Models\User;
use Livewire\Component;

class VisualizarAdm extends Component
{
    public $filtro_id, $fechad, $arqueoant;
    public $cobradoscant, $cobradospesos, $pendientescant, $pendientepesos, $devolucionescant, $devolucionespesos;
    public $bajascant, $bajaspesos, $comision;
    public $totalpesosent, $totalpesosrest, $totalcomision, $porcentajecobrado, $porcentajeent, $porcentajerest;
    public $mesarqueo;
    public $cobradores;
    public function mount()
    {
        $this->cobradores = User::whereIn('escobrador', [1])->orderBy('name')->get();
        $this->filtro_id = null;
        $this->fechad = null;
        $this->arqueoant = 0;
        $emision = Emision::first();
        $this->mesarqueo = $emision->mesarq;
        ///        $this->cobradospesos = Emision::where('cobrador', $this->filtro_id)->where('arqueo', 'C')->sum('total');
        $this->cobradospesos = Emision::where('arqueo', 'C')->sum('total');
        $totaltotal = Emision::count();
        $this->cobradoscant = Emision::where('arqueo', 'C')->count();
        $this->pendientepesos = Emision::where('arqueo', 'P')->sum('total');
        $this->pendientescant = Emision::where('arqueo', 'P')->count();
        $this->devolucionespesos = Emision::where('arqueo', 'D')->sum('total');
        $this->devolucionescant = Emision::where('arqueo', 'D')->count();
        $this->bajaspesos = Emision::where('arqueo', 'B')->sum('total');
        $this->bajascant = Emision::where('arqueo', 'B')->count();
        $this->comision = $this->cobradospesos * 0.07;
        $this->totalpesosent = Cob_entrega::where('arqueo', $this->mesarqueo)->whereIn('valida', [1])->sum('pesos');
        $this->totalpesosrest = $this->totalpesosent - $this->cobradospesos;
        $this->porcentajecobrado = ($this->cobradoscant / $totaltotal)  * 100;
        $this->porcentajerest = ($this->cobradoscant / $this->pendientescant) * 100;
        $this->porcentajerest = 100 - $this->porcentajecobrado;
    }

    public function render()
    {
        return view('livewire.visualizar-adm', [
            'cobradores' => $this->cobradores,
        ]);
    }

    public function sumar()
    {
        if ($this->fechad != null) {
            if ($this->arqueoant != 0) {
                $verifica = date('d-m-Y');
                $arqactual = "arq" . substr($verifica, 3, 2) . substr($verifica, 8, 2);
                $modelo = new Emision();
                if ($arqactual != $this->arqueoant) {
                    if ($this->fechad > $verifica) {
                        $nombrear = "arqueo";
                        $modelo->setTable($nombrear);
                    } else {
                        $nombrear = "arq" . substr($this->fechad, 3, 2) . substr($this->fechad, 8, 2);
                        $modelo->setTable($nombrear);
                    }
                } else {
                    $nombrear = "arqueo";
                    $modelo->setTable($nombrear);
                }
            } else {
                $modelo = new Emision();
                $nombrear = "arqueo";
                $modelo->setTable($nombrear);
            }
            if ($this->filtro_id) {
                $this->cobradospesos = $modelo::where('arqueo', 'C')->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->sum('total');
                $totaltotal = $modelo::count();
                $this->cobradoscant = $modelo::where('arqueo', 'C')->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->count();
                $this->pendientepesos = $modelo::whereIn('arqueo', ['P', 'W', 'V'])->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->sum('total');
                $this->pendientescant = $modelo::whereIn('arqueo', ['P', 'W', 'V'])->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->count();
                $this->devolucionespesos = $modelo::where('arqueo', 'D')->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->sum('total');
                $this->devolucionescant = $modelo::where('arqueo', 'D')->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->count();
                $this->bajaspesos = $modelo::where('arqueo', 'B')->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->sum('total');
                $this->bajascant = $modelo::where('arqueo', 'B')->where('cob', $this->filtro_id)->where('fecha', '<=', $this->fechad)->count();
                $this->comision = $this->cobradospesos * 0.07;
                $this->totalpesosent = Cob_entrega::where('arqueo', $this->mesarqueo)->where('cobrador', $this->filtro_id)->where('fecha', '<=', $this->fechad)->whereIn('valida', [1])->sum('pesos');
                $this->totalpesosrest = $this->totalpesosent - $this->cobradospesos;
                if ($totaltotal > 0) {
                    $this->porcentajecobrado = ($this->cobradoscant / $totaltotal)  * 100;
                } else {
                    $this->porcentajecobrado = 0;
                }
                if ($this->pendientescant > 0) {
                    $this->porcentajerest = ($this->cobradoscant / $this->pendientescant) * 100;
                } else {
                    $this->porcentajerest = 0;
                }
                $this->porcentajerest = 100 - $this->porcentajecobrado;
            } else {
                $this->cobradospesos = $modelo::where('arqueo', 'C')->where('fecha', '<=', $this->fechad)->sum('total');
                $totaltotal = $modelo::count();
                $this->cobradoscant = $modelo::where('arqueo', 'C')->where('fecha', '<=', $this->fechad)->count();
                $this->pendientepesos = $modelo::whereIn('arqueo', ['P', 'W', 'V'])->where('fecha', '<=', $this->fechad)->sum('total');
                $this->pendientescant = $modelo::whereIn('arqueo', ['P', 'W', 'V'])->where('fecha', '<=', $this->fechad)->count();
                $this->devolucionespesos = $modelo::where('arqueo', 'D')->where('fecha', '<=', $this->fechad)->sum('total');
                $this->devolucionescant = $modelo::where('arqueo', 'D')->where('fecha', '<=', $this->fechad)->count();
                $this->bajaspesos = $modelo::where('arqueo', 'B')->where('fecha', '<=', $this->fechad)->sum('total');
                $this->bajascant = $modelo::where('arqueo', 'B')->where('fecha', '<=', $this->fechad)->count();
                $this->comision = $this->cobradospesos * 0.07;
                $this->totalpesosent = Cob_entrega::where('arqueo', $this->mesarqueo)->where('fecha', '<=', $this->fechad)->whereIn('valida', [1])->sum('pesos');
                $this->totalpesosrest = $this->totalpesosent - $this->cobradospesos;
                if ($totaltotal > 0) {
                    $this->porcentajecobrado = ($this->cobradoscant / $totaltotal)  * 100;
                } else {
                    $this->porcentajecobrado = 0;
                }
                if ($this->pendientescant > 0) {
                    $this->porcentajerest = ($this->cobradoscant / $this->pendientescant) * 100;
                } else {
                    $this->porcentajerest = 0;
                }
                $this->porcentajerest = 100 - $this->porcentajecobrado;
            }
        } else {
            if ($this->filtro_id) {
                $this->cobradospesos = Emision::where('arqueo', 'C')->where('cob', $this->filtro_id)->sum('total');
                $totaltotal = Emision::count();
                $this->cobradoscant = Emision::where('arqueo', 'C')->where('cob', $this->filtro_id)->count();
                $this->pendientepesos = Emision::whereIn('arqueo', ['P', 'W', 'V'])->where('cob', $this->filtro_id)->sum('total');
                $this->pendientescant = Emision::whereIn('arqueo', ['P', 'W', 'V'])->where('cob', $this->filtro_id)->count();
                $this->devolucionespesos = Emision::where('arqueo', 'D')->where('cob', $this->filtro_id)->sum('total');
                $this->devolucionescant = Emision::where('arqueo', 'D')->where('cob', $this->filtro_id)->count();
                $this->bajaspesos = Emision::where('arqueo', 'B')->where('cob', $this->filtro_id)->sum('total');
                $this->bajascant = Emision::where('arqueo', 'B')->where('cob', $this->filtro_id)->count();
                $this->comision = $this->cobradospesos * 0.07;
                $this->totalpesosent = Cob_entrega::where('arqueo', $this->mesarqueo)->where('cobrador', $this->filtro_id)->whereIn('valida', [1])->sum('pesos');
                $this->totalpesosrest = $this->totalpesosent - $this->cobradospesos;
                if ($totaltotal > 0) {
                    $this->porcentajecobrado = ($this->cobradoscant / $totaltotal)  * 100;
                } else {
                    $this->porcentajecobrado = 0;
                }
                if ($this->pendientescant > 0) {
                    $this->porcentajerest = ($this->cobradoscant / $this->pendientescant) * 100;
                } else {
                    $this->porcentajerest = 0;
                }
                $this->porcentajerest = 100 - $this->porcentajecobrado;
            } else {
                $this->cobradospesos = Emision::where('arqueo', 'C')->sum('total');
                $totaltotal = Emision::count();
                $this->cobradoscant = Emision::where('arqueo', 'C')->count();
                $this->pendientepesos = Emision::whereIn('arqueo', ['P', 'W', 'V'])->sum('total');
                $this->pendientescant = Emision::whereIn('arqueo', ['P', 'W', 'V'])->count();
                $this->devolucionespesos = Emision::where('arqueo', 'D')->sum('total');
                $this->devolucionescant = Emision::where('arqueo', 'D')->count();
                $this->bajaspesos = Emision::where('arqueo', 'B')->sum('total');
                $this->bajascant = Emision::where('arqueo', 'B')->count();
                $this->comision = $this->cobradospesos * 0.07;
                $this->totalpesosent = Cob_entrega::where('arqueo', $this->mesarqueo)->whereIn('valida', [1])->sum('pesos');
                $this->totalpesosrest = $this->totalpesosent - $this->cobradospesos;
                if ($totaltotal > 0) {
                    $this->porcentajecobrado = ($this->cobradoscant / $totaltotal)  * 100;
                } else {
                    $this->porcentajecobrado = 0;
                }
                if ($this->pendientescant > 0) {
                    $this->porcentajerest = ($this->cobradoscant / $this->pendientescant) * 100;
                } else {
                    $this->porcentajerest = 0;
                }
                $this->porcentajerest = 100 - $this->porcentajecobrado;
            }
        }
    }
}
