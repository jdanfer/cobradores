<?php

namespace App\Http\Livewire;

use App\Models\Cob_entrega;
use App\Models\Emision;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class EntregasCobrador extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $cobradores, $cobrador_id, $mesarqueo, $sumatotal;
    public $perPage = 15;
    public $mensajeExito;
    public $mensajeError;
    public $validar;

    // Propiedades para el modal de edición
    public $showEditModal = false;
    public $entrega_id;
    public $importe;
    public $obs;
    public $showCreateModal = false;
    public $fecha, $hora;
    protected $listeners = ['eliminarEntrega'];

    public function mount()
    {
        $this->cobradores = User::whereIn('escobrador', [1])->orderBy('name')->get();
        $this->fecha = date('Y-m-d');
        $this->hora = date('H:i');

        $arqueo = Emision::first();
        if (isset($arqueo)) {
            $this->mesarqueo = $arqueo->mesarq;
        } else {
            $this->mesarqueo = "Sin Arqueo";
        }
    }

    public function render()
    {
        $baseQuery = $this->sumarEntregas();
        $registrosPaginados = $baseQuery->paginate($this->perPage);

        return view('livewire.entregas-cobrador', [
            'cobradores' => $this->cobradores,
            'mesarqueo' => $this->mesarqueo,
            'entregas' => $registrosPaginados,
        ]);
    }

    public function ingresarEntrega()
    {
        if (auth()->user()->hcerol_id != 1) {
            $this->cobrador_id = auth()->user()->cod_sapp;
        } else {
            $this->cobrador_id = $this->cobrador_id;
        }

        if (!$this->cobrador_id) {
            $this->mensajeError = 'Debe seleccionar un cobrador';
            return;
        }
        $this->resetErrorBag();
        $this->reset(['importe', 'obs', 'entrega_id']);
        $this->fecha = date('Y-m-d');
        $this->hora = date('H:i');
        $this->showCreateModal = true;
    }

    public function guardarEntrega()
    {
        $this->validate([
            'importe' => 'required|numeric|min:1',
            'fecha'   => 'required|date',
            'hora'    => 'required',
            'obs'     => 'nullable|string|max:255',
        ]);
        try {
            if (auth()->user()->hcerol_id != 1) {
                $this->cobrador_id = auth()->user()->cod_sapp;
            } else {
                $this->cobrador_id = $this->cobrador_id;
            }
            if (!$this->cobrador_id) {
                $this->mensajeError = 'Debe seleccionar un cobrador';
                return;
            }
            Cob_entrega::create([
                'cobrador' => $this->cobrador_id,
                'pesos'    => $this->importe,
                'fecha'    => $this->fecha,
                'hora'   => $this->hora,
                'obs'      => $this->obs,
                'arqueo'   => $this->mesarqueo,
                'usuario'  => auth()->user()->name,
                'valida'   => $this->validar ? 1 : null,
                // Asegúrate de incluir otros campos obligatorios de tu tabla aquí
            ]);

            $this->mensajeExito = 'Entrega registrada con éxito';
            $this->showCreateModal = false;
            $this->reset(['importe', 'obs']);
        } catch (\Exception $e) {
            $this->mensajeError = 'Error al guardar: ' . $e->getMessage();
        }
    }

    public function sumarEntregas()
    {
        if (auth()->user()->hcerol_id == 1) {
            if ($this->cobrador_id) {
                $query = Cob_entrega::where('cobrador', $this->cobrador_id)->where('arqueo', $this->mesarqueo);
                $this->sumatotal = Cob_entrega::where('cobrador', $this->cobrador_id)
                    ->where('arqueo', $this->mesarqueo)->sum('pesos');
            } else {
                $query = Cob_entrega::where('arqueo', $this->mesarqueo);
                $this->sumatotal = Cob_entrega::where('arqueo', $this->mesarqueo)->sum('pesos');
            }
        } else {
            $query = Cob_entrega::where('cobrador', auth()->user()->cod_sapp)->where('arqueo', $this->mesarqueo);
            $this->sumatotal = Cob_entrega::where('cobrador', auth()->user()->cod_sapp)->whereNotNull('valida')
                ->where('arqueo', $this->mesarqueo)->sum('pesos');
        }
        return $query->orderBy('fecha', 'desc');
    }

    public function editarEntrega($id)
    {
        $entrega = Cob_entrega::find($id);
        if ($entrega->valida == 1) {
            $this->mensajeError = 'No se puede editar una entrega validada';
            return;
        }
        if ($entrega) {
            $this->entrega_id = $entrega->id;
            $this->importe = $entrega->pesos;
            $this->obs = $entrega->obs;
            $this->showEditModal = true;
            $this->validar = $entrega->valida;
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function actualizarEntrega()
    {
        $this->validate([
            'importe' => 'required|numeric|min:0',
            'obs' => 'nullable|string|max:255',
        ], [
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser un número',
            'importe.min' => 'El importe debe ser mayor o igual a 0',
            'obs.max' => 'La observación no puede exceder 255 caracteres',
        ]);

        try {
            $entrega = Cob_entrega::find($this->entrega_id);

            if ($entrega) {
                $entrega->pesos = $this->importe;
                $entrega->obs = $this->obs;
                $entrega->valida = $this->validar ? 1 : null;
                $entrega->save();

                $this->mensajeExito = 'Entrega actualizada correctamente';
                $this->cerrarModal();
                $this->resetPage();
            } else {
                $this->mensajeError = 'No se pudo actualizar el registro';
            }
        } catch (\Exception $e) {
            $this->mensajeError = 'Error al actualizar: ' . $e->getMessage();
        }
    }

    public function cerrarModal()
    {
        $this->showEditModal = false;
        $this->showCreateModal = false;
        $this->reset(['entrega_id', 'importe', 'obs', 'fecha', 'hora']);
        $this->resetErrorBag();
    }

    public function borrarEntrega($id)
    {
        // Emitir evento a JavaScript
        $this->emit('confirmar-borrar-entrega', $id);
    }

    public function eliminarEntrega($id)
    {
        try {
            $entrega = Cob_entrega::find($id);
            if ($entrega->valida == 1) {
                $this->mensajeError = 'No se puede eliminar una entrega validada';
                return;
            }
            if ($entrega) {
                $entrega->delete();
                $this->mensajeExito = 'Entrega eliminada correctamente';
                $this->resetPage();
            } else {
                $this->mensajeError = 'No se encontró el registro a eliminar';
            }
        } catch (\Exception $e) {
            $this->mensajeError = 'Error al eliminar: ' . $e->getMessage();
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['cobrador_id', 'perPage'])) {
            $this->resetPage();
        }
    }
}
