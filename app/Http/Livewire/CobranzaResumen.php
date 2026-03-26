<?php

namespace App\Http\Livewire;

use App\Models\Abm_arqueo;
use App\Models\Cob_filtro;
use App\Models\Cob_motivo;
use App\Models\deuda;
use App\Models\Emision;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class CobranzaResumen extends Component
{
    use WithPagination;
    public $cobradores, $cobrador_id, $nombre, $cedula, $edocumento, $fechad, $fechah;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 15;
    public $registrosIds = [];
    public $sumatotal = 0;
    public $mensajeExito, $filtro_id, $filtros;
    public $mensajeError, $grupof;
    public $modalMotivo = false;
    public $tipoAccion = ''; // 'baja' o 'devolucion'
    public $motivoSeleccionado = null;
    public $emisionIdPendiente = null;
    public $motivos = [];

    public function mount()
    {
        $this->cobradores = User::whereIn('escobrador', [1])->orderBy('name')->get();
        $this->filtros = Cob_filtro::whereIn('id', [1, 2, 7, 8])->get();
        $this->motivos = Cob_motivo::whereIn('opcion', [1])->get();
    }

    public function render()
    {
        $baseQuery = $this->sumarCobranza();

        // Paginar los resultados
        $registrosPaginados = $baseQuery->paginate($this->perPage);
        // Almacenar solo los IDs de los registros actuales
        $this->registrosIds = $registrosPaginados->pluck('nro')->toArray();

        return view('livewire.cobranza-resumen', [
            'cobradores' => $this->cobradores,
            'registros' => $registrosPaginados,
            'filtros' => $this->filtros,
            'motivos' => $this->motivos,
        ]);
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
        $this->mensajeExito = null;
        $this->mensajeError = null;
        $emision = Emision::find($id);

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        $matricula = $emision->matricula;
        $recibo = $emision->nrorec;
        $mesarq = $emision->mesarq;

        $emision->arqueo = 'P';
        $elmotivo = Cob_motivo::find($this->motivoSeleccionado);
        if ($elmotivo) {
            $emision->motivo = $elmotivo->descrip; // Guardar el motiv
        } else {
            $emision->motivo = "Sin datos";
        }
        $emision = $emision->save();
        $deudap = deuda::where('DOCUMENTO', $recibo)->where('CLIENTE', $matricula)->whereNotNull('fecha_pago')->first();
        if (isset($deudap)) {
            $deudap->fecha_pago = null;
            $deudap->desdeapp = "Devolución Cobranza";
            $deudap->fecha_anula = date('Y-m-d');
            $deudap->save();
        }
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "P";
        $historial->documento = $recibo;
        $historial->matricula = $matricula;
        $historial->obs = "Devol.Cobro";
        $historial->save();

        $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', (auth()->user()->cod_sapp))->sum('total');

        $this->mensajeExito = 'El registro pasó a pendientes!';
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
        $emision->arqueo = 'P';
        $emision->fecha = date('Y-m-d');
        $emision->usuar = (auth()->user()->username);
        $emision->hora = date('H:i');
        $emision->save();
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "P";
        $historial->documento = $emision->nrorec;
        $historial->matricula = $emision->matricula;
        $historial->obs = "Anula pago";
        $historial->save();
        $this->mensajeExito = 'Se anula el pago y se vuelve a pendientes.';
    }

    public function sumarCobranza()
    {
        $desdehoy = date('Y-m-d');
        if ($this->cobrador_id) {
            $query = Emision::where('cob', $this->cobrador_id)->whereIn('arqueo', ['C']);
            if ($this->filtro_id == 1) {
                $this->sumatotal = Emision::where('cob', $this->cobrador_id)
                    ->where('arqueo', 'C')->where('fecha', '>=', date('Y-m-d', strtotime($this->fechad)))->where('fecha', '<=', date('Y-m-d', strtotime($this->fechah)))->sum('total');
            } else {
                $this->sumatotal = Emision::where('cob', $this->cobrador_id)
                    ->where('arqueo', 'C')->sum('total');
            }
        } else {
            if (auth()->user()->hcerol_id == 1) {
                $query = Emision::whereIn('arqueo', ['C']);
                $this->sumatotal = Emision::where('arqueo', 'C')->sum('total');
            } else {
                $query = Emision::whereIn('arqueo', ['C'])->where('cob', auth()->user()->cod_sapp)->where('fecha', $desdehoy);
                $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', auth()->user()->cod_sapp)->where('fecha', $desdehoy)->sum('total');
            }
        }
        if ($this->grupof) {
            $query->orderBy('grupof', 'asc');
        }
        if ($this->filtro_id == 1) {
            $query->where("fecha", ">=", date('Y-m-d', strtotime($this->fechad)))->where('fecha', "<=", date('Y-m-d', strtotime($this->fechah)));
        }
        if ($this->filtro_id == 2) {
            $query->where("nombre", "like", "%" . trim($this->nombre) . "%");
        }
        if ($this->filtro_id == 7) {
            $query->where("usuario_ced", "like", "%" . trim($this->cedula) . "%");
        }
        if ($this->filtro_id == 8) {
            $query->where("nrorec", "like", "%" . trim($this->edocumento) . "%");
        }

        return $query->orderBy('fecha', 'desc');
    }

    public function confirmarImpresionIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->confirmarImpresionTabla($this->registrosIds[$index]); // Cambiar aquí
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function confirmarImpresionTabla($id)
    {
        // NUEVO: Este es para el botón de la tabla
        $this->dispatchBrowserEvent('imprime-cobro', ['id' => $id]);
    }

    public function imprimirTicket($id)
    {
        $emision = Emision::find($id);
        $this->mensajeExito = null;
        $this->mensajeError = null;

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        $emision->reimp = 1;
        $emision->save();
        // Aquí va la lógica para imprimir el ticket
        if (isset($emision)) {
            $tipocobro = "RECIBO DE COBRO";
            $fechaformat = date("d-m-Y");
            $historial = new Abm_arqueo();
            $historial->fecha = date('Y-m-d');
            $historial->hora = date('H:i');
            $historial->usuario = (auth()->user()->username);
            $historial->base = 99;
            $historial->movimiento = "C";
            $historial->documento = $emision->nrorec;
            $historial->matricula = $emision->matricula;
            $historial->obs = "RE-Imprime";
            $historial->save();

            if ($emision->ruc != null) {
                $tipo = 'E-FACTURA';
                $consumidor = "FACTURA";
                $tipodoc = "RUC:";
                $nroidentif = $emision->ruc;
            } else {
                $tipo = 'E-TICKET';
                $consumidor = "CONSUMIDOR FINAL";
                $tipodoc = "Otro:";
                $nroidentif = $emision->matricula;
            }
            $datosTicket = [
                'tipocobro' => $tipocobro,
                'tipofactura' => $tipo,
                'serie' => $emision->serie,
                'forma_pago' => "CREDITO",
                'consumidor' => $consumidor,
                'tipodoc' => $tipodoc,
                'nroidentif' => $nroidentif,
                'fechadocu' => $fechaformat,
                'numero' => $emision->nrorec,
                'cliente' => $emision->nombre,
                'telefono' => $emision->tel_cli,
                'codigoseg' => $emision->codigoseg,
                'nrocae' => $emision->nrocae,
                'vencimientocae' => $emision->vencimientocae,
                'adenda' => $emision->adenda,
                'direccion' => $emision->dir_cli,
                'fecha' => $emision->mes . '/' . $emision->ano,
                'dias_cobro' => $emision->dia1 . ' al ' . $emision->dia2,
                'cuota' => number_format($emision->importe, 2, ',', '.'),
                'tiquet' => number_format($emision->tiquet, 2, ',', '.'),
                'deudas' => number_format($emision->deudas, 2, ',', '.'),
                'promocion' => number_format($emision->descimp, 2, ',', '.'),
                'iva' => number_format($emision->iva, 2, ',', '.'),
                'total' => number_format($emision->total, 2, ',', '.'),
            ];

            $this->dispatchBrowserEvent('imprimir-ticket-arq', ['datos' => $datosTicket]);

            $this->mensajeExito = 'Ticket impreso correctamente';
        } else {
            $this->mensajeError = 'Error al actualizar la emisión';
            return;
        }
    }

    public function solicitarMotivoDevolucion($id)
    {
        $this->emisionIdPendiente = $id;
        $this->tipoAccion = 'devolucion';
        $this->motivoSeleccionado = null;
        $this->modalMotivo = true;
    }

    public function procesarConMotivo()
    {
        if (!$this->motivoSeleccionado) {
            $this->mensajeError = 'Debe seleccionar un motivo';
            return;
        }

        if ($this->tipoAccion === 'baja') {
            $this->confirmarBajaArqueo($this->emisionIdPendiente);
        } elseif ($this->tipoAccion === 'devolucion') {
            $this->confirmarDevolArqueo($this->emisionIdPendiente);
        }

        $this->cerrarModalMotivo();
    }

    public function cerrarModalMotivo()
    {
        $this->modalMotivo = false;
        $this->tipoAccion = '';
        $this->emisionIdPendiente = null;
        $this->motivoSeleccionado = null;
    }

    public function solicitarMotivoDevolucionIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->solicitarMotivoDevolucion($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }
}