<?php

namespace App\Http\Livewire;

use App\Models\Abm_arqueo;
use App\Models\Cob_filtro;
use App\Models\deuda;
use App\Models\Emision;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Validaradm extends Component
{
    use WithPagination;
    public $cobradores, $cobrador_id, $grupof, $cedula, $edocumento;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 15;
    public $registrosIds = [];
    public $sumatotal = 0;
    public $mensajeExito, $filtro_id, $filtros;
    public $mensajeError;

    public function mount()
    {
        $this->cobradores = User::whereIn('escobrador', [1])->orderBy('name')->get();
        $this->filtros = Cob_filtro::whereIn('id', [7, 8, 9])->get();
    }

    public function render()
    {
        $baseQuery = $this->sumarCobranza();

        // Paginar los resultados
        $registrosPaginados = $baseQuery->paginate($this->perPage);
        // Almacenar solo los IDs de los registros actuales
        $this->registrosIds = $registrosPaginados->pluck('nro')->toArray();

        return view('livewire.validaradm', [
            'cobradores' => $this->cobradores,
            'registros' => $registrosPaginados,
            'filtros' => $this->filtros,
        ]);
    }

    public function sumarCobranza()
    {
        if ($this->cobrador_id) {
            $query = Emision::where('cob', $this->cobrador_id)->whereIn('arqueo', ['W', 'V']);
            $this->sumatotal = Emision::where('cob', $this->cobrador_id)
                ->whereIn('arqueo', ['W', 'V'])->sum('total');
        } else {
            $query = Emision::whereIn('arqueo', ['W', 'V']);
            $this->sumatotal = Emision::whereIn('arqueo', ['W', 'V'])->sum('total');
        }
        if ($this->grupof) {
            $query->orderBy('grupof', 'asc');
        }
        if ($this->filtro_id == 7) {
            $query->where('usuario_ced', 'like', '%' . $this->cedula . '%');
        }
        if ($this->filtro_id == 8) {
            $query->where('nrorec', 'like', '%' . $this->edocumento . '%');
        }
        return $query->orderBy('fecha', 'desc');
    }

    public function guardarArqueoDIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->confirmarDevolArqueo($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function confirmarDevolArqueo($id)
    {
        // NUEVO: Este es para el botón de la tabla
        $this->dispatchBrowserEvent('confirmar-devol', ['id' => $id]);
    }

    public function guardarArqueoD($id)
    {
        $emision = Emision::find($id);
        $this->mensajeExito = null;
        $this->mensajeError = null;

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        if ($emision->arqueo == 'W') {
            $emision->arqueo = 'D';
            $movimiento = "D";
        } elseif ($emision->arqueo == 'V') {
            $emision->arqueo = 'B';
            $movimiento = "B";
        }
        $emision->fecha = date('Y-m-d');
        $emision->usuar = (auth()->user()->username);
        $emision->hora = date('H:i');
        $emision->save();
        $deudas = deuda::where('DOCUMENTO', $emision->nrorec)->where('CLIENTE', $emision->matricula)->first();
        if ($deudas) {
            if ($movimiento == "D") {
                if ($deudas->FECHA_PAGO == null) {
                    $deudas->FECHA_PAGO = date('Y-m-d');
                    $deudas->save();
                }
            } else {
                if ($deudas->FECHA_PAGO != null) {
                    $this->mensajeError = 'No se puede pasar a baja en deudas por tener fecha de pago.';
                    return;
                }
            }
        }
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = $movimiento;
        $historial->documento = $emision->nrorec;
        $historial->matricula = $emision->matricula;
        $historial->obs = "Valida Adm " . $movimiento;
        $historial->save();
        $this->mensajeExito = 'Se ha registrado correctamente.';
    }

    public function guardarArqueoPIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->confirmarCancelaArqueo($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function confirmarCancelaArqueo($id)
    {
        // NUEVO: Este es para el botón de la tabla
        $this->dispatchBrowserEvent('confirmar-cancela', ['id' => $id]);
    }

    public function guardarArqueoP($id)
    {
        $emision = Emision::find($id);
        $this->mensajeExito = null;
        $this->mensajeError = null;

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        $emision->arqueo = 'P';
        $emision->fecha = date('Y-m-d');
        $emision->usuar = (auth()->user()->username);
        $emision->hora = date('H:i');
        $emision->save();
        $deudas = deuda::where('DOCUMENTO', $emision->nrorec)->where('CLIENTE', $emision->matricula)->first();
        if (isset($deudas)) {
            if ($deudas->FECHA_PAGO != null) {
                $this->mensajeError = 'ERROR: No se puede pasar a pendiente en deudas por tener fecha de pago.';
                return;
            }
        }
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "P";
        $historial->documento = $emision->nrorec;
        $historial->matricula = $emision->matricula;
        $historial->obs = "Valida Adm a Pend.";
        $historial->save();
        $this->mensajeExito = 'Se ha registrado correctamente en arqueo.';
    }
}
