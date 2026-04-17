<?php

namespace App\Http\Livewire;

use App\Models\Abm_arqueo;
use App\Models\Cliente;
use App\Models\Cob_entrega;
use App\Models\Cob_filtro;
use App\Models\Cob_motivo;
use App\Models\deuda;
use App\Models\Emision;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class Visualizar extends Component
{
    use WithPagination;
    public $mostrarMapa = false;
    public $primeraUbicacion;
    public $desde = [];
    public $hasta = [];
    public $fechad, $fechah;
    public $filtros, $filtro_id, $nombre, $telefono, $direccion, $cedula, $edocumento, $zona, $nombrecli, $matricula;
    public $mensajeExito, $grupof, $grupofam;
    public $mensajeError, $sumatotal = 0, $sumatotalpend = 0, $haynuevas = 0;
    public $perPage = 15;
    public $faltaentregar = 0;
    public $modalDetalle = false;
    public $facturaSeleccionada = null;
    public $detalleFactura = [];
    public $registrosIds = []; // Cambio: almacenar solo IDs
    public $mapaFiltroNro = null; // Filtro por marcador del mapa

    public $modalMotivo = false;
    public $tipoAccion = ''; // 'baja' o 'devolucion'
    public $emisionIdPendiente = null;
    public $motivoSeleccionado = null;
    public $motivos = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->filtros = Cob_filtro::all();
        $this->motivos = Cob_motivo::all();
        $this->primeraUbicacion = $this->getFirstLocation();
        $primer = Emision::first();
        $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', (auth()->user()->cod_sapp))->sum('total');
        $this->sumatotalpend = Cob_entrega::where('arqueo', $primer->mesarq)->where('cobrador', (auth()->user()->cod_sapp))->sum('pesos');
        $this->faltaentregar = $this->sumatotal - $this->sumatotalpend;
        $this->haynuevas = Emision::where('cob', (auth()->user()->cod_sapp))->whereIn('nuevo', [1])->count();
    }

    public function cargarRegistros()
    {
        $primer = Emision::first();

        $query = Emision::whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E']);
        if ($this->filtro_id == 7) {
            $query->where("usuario_ced",  $this->cedula);
        }
        if ($this->filtro_id == 8) {
            $query->where("nrorec",  $this->edocumento);
        }
        if ($this->filtro_id == 8) {
            $query->where("nrorec",  $this->edocumento);
        }
        if ($this->filtro_id == 9) {
            $query->where("loc_cli",  $this->zona);
        }
        if ($this->filtro_id == 10) {
            $query->where("matricula",  $this->matricula);
        }

        if ($this->filtro_id == 6) {
            $query->whereIn("nuevo",  [1, 2]);
            Emision::whereIn("nuevo",  [1, 2])->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->update(['nuevo' => 2]);
        }
        if ($this->filtro_id == 5) {
            $query->where("grupof",  $this->grupofam);
        }
        if ($this->filtro_id == 2) {
            $query->where("nombre", "like", "%" . trim($this->nombre) . "%");
        } else {
            if ($this->filtro_id == 4) {
                $query->where("tel_cli", "like", "%" . trim($this->telefono) . "%");
            } else {
                if ($this->filtro_id == 3) {
                    $query->where("dir_cli", "like", "%" . trim($this->direccion) . "%");
                } else {
                    if ($this->filtro_id == 1) {
                        $fechad = $this->fechad ? date('Y-m-d', strtotime($this->fechad)) : null;
                        $fechah = $this->fechah ? date('Y-m-d', strtotime($this->fechah)) : null;
                        $fecha1 = substr($fechad, 8, 2);
                        $fecha2 = substr($fechah, 8, 2);
                        $query->where("dia1", ">=", $fecha1)->where("dia2", "<=", $fecha2);
                    } else {
                        $this->nombre = '';
                        $this->telefono = '';
                        $this->direccion = '';
                    }
                }
            }
        }
        if ($this->mapaFiltroNro) {
            $query->where('nro', $this->mapaFiltroNro);
        }
        if ($this->grupof) {
            $query->orderBy('grupof', 'desc');
        }
        return $query->orderBy('matricula', 'asc')->orderBy('fecha', 'asc');
        $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', (auth()->user()->cod_sapp))->sum('total');
        $this->sumatotalpend = Cob_entrega::where('arqueo', $primer->mesarq)->where('cobrador', (auth()->user()->cod_sapp))->sum('pesos');
        $this->faltaentregar = $this->sumatotal - $this->sumatotalpend;
    }

    public function getFirstLocation()
    {
        if ($this->filtro_id == 7) {
            $primerRegistro = Emision::where("usuario_ced",  $this->cedula)
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        }
        if ($this->filtro_id == 8) {
            $primerRegistro = Emision::where("nrorec",  $this->edocumento)
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        }
        if ($this->filtro_id == 9) {
            $primerRegistro = Emision::where("loc_cli",  $this->zona)
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        }
        if ($this->filtro_id == 10) {
            $primerRegistro = Emision::where("matricula",  $this->matricula)
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        }
        if ($this->filtro_id == 6) {
            $primerRegistro = Emision::whereIn("nuevo",  [1, 2])
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        }
        if ($this->filtro_id == 5) {
            $primerRegistro = Emision::where("grupof",  $this->grupofam)
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        }
        if ($this->filtro_id == 2) {
            $primerRegistro = Emision::where("nombre", "like", "%" . trim($this->nombre) . "%")
                ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
        } else {
            if ($this->filtro_id == 4) {
                $primerRegistro = Emision::where("tel_cli", "like", "%" . trim($this->telefono) . "%")
                    ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
            } else {
                if ($this->filtro_id == 1) {
                    $fechad = $this->fechad ? date('Y-m-d', strtotime($this->fechad)) : null;
                    $fechah = $this->fechah ? date('Y-m-d', strtotime($this->fechah)) : null;
                    $fecha1 = substr($fechad, 8, 2);
                    $fecha2 = substr($fechah, 8, 2);
                    $primerRegistro = Emision::where("dia1", ">=", $fecha1)->where("dia2", "<=", $fecha2)
                        ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
                } else {
                    if ($this->filtro_id == 3) {
                        $primerRegistro = Emision::where("dir_cli", "like", "%" . trim($this->direccion) . "%")
                            ->whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E'])->first();
                    } else {
                        $this->nombre = '';
                        $primerRegistro = Emision::whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))
                            ->whereNotNull('longitud')->whereIn('arqueo', ['P', 'E'])
                            ->first();
                    }
                }
            }
        }

        if ($primerRegistro) {
            return [
                'lat' => $primerRegistro->latitud,
                'lng' => $primerRegistro->longitud
            ];
        } else {
            return [
                'lat' => -34.76844,
                'lng' => -55.72524
            ];
        }
    }

    public function toggleMapa()
    {
        $this->mostrarMapa = !$this->mostrarMapa;
        if ($this->mostrarMapa) {
            $this->dispatchBrowserEvent('mapa-actualizado', [
                'markersData' => $this->markers_data,
                'primeraUbicacion' => $this->primeraUbicacion
            ]);
        }
    }

    public function render()
    {
        // Obtener la consulta base
        $baseQuery = $this->cargarRegistros();

        // Paginar los resultados
        $registrosPaginados = $baseQuery->paginate($this->perPage);

        // Almacenar solo los IDs de los registros actuales
        $this->registrosIds = $registrosPaginados->pluck('nro')->toArray();

        // Inicializar arrays desde/hasta con los registros de la página actual
        $this->desde = [];
        $this->hasta = [];
        foreach ($registrosPaginados as $emision) {
            $this->desde[$emision->nro] = $emision->dia1;
            $this->hasta[$emision->nro] = $emision->dia2;
        }

        // Actualizar primera ubicación
        $this->primeraUbicacion = $this->getFirstLocation();

        return view('livewire.visualizar', [
            'filtros' => $this->filtros,
            'registros' => $registrosPaginados,
            'motivos' => $this->motivos,
        ]);
    }

    public function guardarDesde($id)
    {
        $this->mensajeExito = null;
        $this->mensajeError = null;
        $emision = Emision::find($id);
        $conerror = 0;

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }

        $emision->dia1 = $this->desde[$id] ?? 0;
        $emision->dia2 = $this->hasta[$id] ?? 0;
        $emision->save();
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "M";
        $historial->documento = $emision->nrorec;
        $historial->matricula = $emision->matricula;
        $historial->obs = "Cambia día cobro";
        $historial->save();

        $cliente = Cliente::where('CL_CODIGO', $emision->cliente)->first();
        if (isset($cliente)) {
            $cliente->dia1 = $this->desde[$id] ?? 0;
            $cliente->dia2 = $this->hasta[$id] ?? 0;
            $cliente->save();
        } else {
            $conerror = 1;
        }

        if ($conerror == 0) {
            $this->mensajeExito = 'Actualizado correctamente';
        } else {
            $this->mensajeExito = 'Actualizado pero el cliente no se encontró';
        }
    }

    public function guardarArqueoB($id)
    {
        $emision = Emision::find($id);
        $this->mensajeExito = null;
        $this->mensajeError = null;

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        $emision->arqueo = 'V';
        $emision->fecha = date('Y-m-d');
        $emision->usuar = (auth()->user()->username);
        $emision->hora = date('H:i');
        $emision->save();
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "W";
        $historial->documento = $emision->nrorec;
        $historial->matricula = $emision->matricula;
        $historial->obs = "Desde App";
        $historial->save();

        $this->mensajeExito = 'Registro actualizado a Baja. Será confirmado por Administración';
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
        $emision->arqueo = 'W';
        $emision->fecha = date('Y-m-d');
        $emision->usuar = (auth()->user()->username);
        $emision->hora = date('H:i');
        $emision->save();
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "W";
        $historial->documento = $emision->nrorec;
        $historial->matricula = $emision->matricula;
        $historial->obs = "Desde App";
        $historial->save();
        $this->mensajeExito = 'Registro actualizado a Devolución. Será confirmado por Administración';
    }

    public function confirmarImpresion($id)
    {
        $this->dispatchBrowserEvent('confirmar-impresion', ['id' => $id]);
    }

    public function confirmarImpresionTabla($id)
    {
        // NUEVO: Este es para el botón de la tabla
        $this->dispatchBrowserEvent('imprime-cobro', ['id' => $id]);
    }

    public function confirmarBajaArqueo($id)
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

        $emision->arqueo = 'V';
        $elmotivo = Cob_motivo::find($this->motivoSeleccionado);
        if ($elmotivo) {
            $emision->motivo = $elmotivo->descrip; // Guardar el motiv
        } else {
            $emision->motivo = "Sin datos";
        }
        $emision = $emision->save();
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "B";
        $historial->documento = $recibo;
        $historial->matricula = $matricula;
        $historial->obs = $elmotivo ? $elmotivo->descrip : "Sin datos";
        $historial->save();

        $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', (auth()->user()->cod_sapp))->sum('total');
        $this->sumatotalpend = Cob_entrega::where('arqueo', $mesarq)->where('cobrador', (auth()->user()->cod_sapp))->sum('pesos');
        $this->faltaentregar = $this->sumatotal - $this->sumatotalpend;

        $this->mensajeExito = 'Baja registrada correctamente';
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

        $emision->arqueo = 'W';
        $elmotivo = Cob_motivo::find($this->motivoSeleccionado);
        if ($elmotivo) {
            $emision->motivo = $elmotivo->descrip; // Guardar el motiv
        } else {
            $emision->motivo = "Sin datos";
        }
        $emision = $emision->save();
        $deudap = deuda::where('DOCUMENTO', $recibo)->where('CLIENTE', $matricula)->whereNull('fecha_pago')->first();
        if (isset($deudap)) {
            $deudap->fecha_pago = date('Y-m-d');
            $deudap->desdeapp = "Devol.Cob";
            $deudap->save();
        }
        $historial = new Abm_arqueo();
        $historial->fecha = date('Y-m-d');
        $historial->hora = date('H:i');
        $historial->usuario = (auth()->user()->username);
        $historial->base = 99;
        $historial->movimiento = "D";
        $historial->documento = $recibo;
        $historial->matricula = $matricula;
        $historial->obs = $elmotivo ? $elmotivo->descrip : "Sin datos";
        $historial->save();

        $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', (auth()->user()->cod_sapp))->sum('total');
        $this->sumatotalpend = Cob_entrega::where('arqueo', $mesarq)->where('cobrador', (auth()->user()->cod_sapp))->sum('pesos');
        $this->faltaentregar = $this->sumatotal - $this->sumatotalpend;

        $this->mensajeExito = 'Devolución registrada correctamente';
    }

    public function imprimirTicket($id) //confirmación del cobro del e-ticket o e-factura
    {
        $emision = Emision::find($id);
        $this->mensajeExito = null;
        $this->mensajeError = null;
        
        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        $fechamod = date("Y-m-d", strtotime($emision->fechaemi));
        $verificaemision = Emision::where('fechaemi', '<', $fechamod)->where('matricula', $emision->matricula)->whereIn('arqueo', ['P', 'E'])->get();
        ///dd($verificaemision);
        if ($verificaemision->count() > 0) {
            $this->mensajeError = 'No se puede efectuar el pago porque hay deudas anteriores sin cobrar.';
            return;
        }

        // Aquí va la lógica para imprimir el ticket
        if (isset($emision)) {
            $tipocobro = "RECIBO DE COBRO";
            $fechaformat = date("d-m-Y");
            $emision->arqueo = "C";
            $emision->hora = date('H:i');
            $emision->fecha = date('Y-m-d');
            $emision->usuar = (auth()->user()->username);
            $emision->save();
            $deudas = deuda::where('DOCUMENTO', $emision->nrorec)->where('CLIENTE', $emision->matricula)->first();
            if (isset($deudas)) {
                if ($deudas->FECHA_PAGO != null) {
                    $this->mensajeError = 'ERROR: No se puede actualizar en deudas por tener fecha de pago.';
                    return;
                } else {
                    $deudas->FECHA_PAGO = date('Y-m-d');
                    $deudas->desdeapp = "Cobranza domiciliaria";
                    $deudas->save();
                }
            } else {
                $this->mensajeError = 'ERROR: No se encontró el registro en deudas.';
            }
            $this->sumatotal = Emision::where('arqueo', 'C')->where('cob', (auth()->user()->cod_sapp))->sum('total');
            $this->sumatotalpend = Cob_entrega::where('arqueo', $emision->mesarq)->where('cobrador', (auth()->user()->cod_sapp))->sum('pesos');
            $this->faltaentregar = $this->sumatotal - $this->sumatotalpend;

            $historial = new Abm_arqueo();
            $historial->fecha = date('Y-m-d');
            $historial->hora = date('H:i');
            $historial->usuario = (auth()->user()->username);
            $historial->base = 99;
            $historial->movimiento = "C";
            $historial->documento = $emision->nrorec;
            $historial->matricula = $emision->matricula;
            $historial->obs = "Impresión desde App";
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
                'usuariocob' => auth()->user()->name,
                'fecha' => $emision->mes . '/' . $emision->ano,
                'dias_cobro' => $emision->dia1 . ' al ' . $emision->dia2,
                'cuota' => number_format($emision->importe, 2, ',', '.'),
                'tiquet' => number_format($emision->tiquet, 2, ',', '.'),
                'deudas' => number_format($emision->deudas, 2, ',', '.'),
                'promocion' => number_format($emision->descimp, 2, ',', '.'),
                'iva' => number_format($emision->iva, 2, ',', '.'),
                'total' => number_format($emision->total, 2, ',', '.'),
            ];
            $url = $this->generarPdfTicket80($datosTicket);
            /// hoy 12/3 $this->dispatchBrowserEvent('imprimir-ticket-arq', ['datos' => $datosTicket]);
            $this->dispatchBrowserEvent('abrir-pdf', [
                'url' => $url
            ]);
            $tienemas = Emision::where('matricula', $emision->matricula)->whereIn('arqueo', ['P', 'E'])->get();
            foreach ($tienemas as $item) {
                $item->yagestionado = 1;
                $item->save();
            }
            $this->mensajeExito = 'Ticket impreso correctamente';
        } else {
            $this->mensajeError = 'Error al actualizar la emisión';
            return;
        }
    }

    public function imprimirTicketTermica($id)
    {
        $emision = Emision::find($id);
        $this->mensajeExito = null;
        $this->mensajeError = null;

        if (!$emision) {
            $this->mensajeError = 'Emisión no encontrada';
            return;
        }
        $fechaformat = date("d-m-Y", strtotime($emision->fecha));
        // Preparar datos para la impresión térmica
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
            'usuariocob' => auth()->user()->name,
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
        $url = $this->generarPdfTicket80Gfe($datosTicket);
        $this->dispatchBrowserEvent('abrir-pdf', [
            'url' => $url
        ]);
        // Emitir evento para JavaScript que maneja la impresora térmica
        ///modif 12/3        $this->dispatchBrowserEvent('imprimir-ticket-termica', ['datos' => $datosTicket]);

        $this->mensajeExito = 'Enviando ticket a impresora...';
        $this->cerrarModal();
    }

    // Métodos que usan el índice
    public function guardarDesdeIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->guardarDesde($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function confirmarImpresionIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            // Obtenemos el ID
            $id = $this->registrosIds[$index];

            // Buscamos el registro para obtener el nombre
            $emision = Emision::find($id);
            $nombreCliente = $emision ? $emision->nombre : 'Cliente desconocido';

            // Despachamos el evento incluyendo el nombre
            $this->dispatchBrowserEvent('imprime-cobro', [
                'id' => $id,
                'nombre' => $nombreCliente
            ]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function guardarArqueoBIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->confirmarBajaArqueo($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function guardarArqueoDIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->confirmarDevolArqueo($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function mostrarDetalleIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->mostrarDetalle($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function getMarkersDataProperty()
    {
        //ACAA Obtener TODOS los registros para el mapa (no solo los de la página actual)
        $query = Emision::whereNotNull('latitud')->where('cob', (auth()->user()->cod_sapp))->whereIn('arqueo', ['P', 'E']);

        if ($this->filtro_id == 2) {
            $query->where("nombre", "like", "%" . trim($this->nombre) . "%");
        } else {
            if ($this->filtro_id == 4) {
                $query->where("tel_cli", "like", "%" . trim($this->telefono) . "%");
            } else {
                if ($this->filtro_id == 3) {
                    $query->where("dir_cli", "like", "%" . trim($this->direccion) . "%");
                } else {
                    if ($this->filtro_id == 1) {
                        $fechad = $this->fechad ? date('Y-m-d', strtotime($this->fechad)) : null;
                        $fechah = $this->fechah ? date('Y-m-d', strtotime($this->fechah)) : null;
                        $fecha1 = substr($fechad, 8, 2);
                        $fecha2 = substr($fechah, 8, 2);
                        $query->where("dia1", ">=", $fecha1)->where("dia2", "<=", $fecha2);
                    } else {
                        $this->nombre = '';
                        $this->telefono = '';
                        $this->direccion = '';
                    }
                }
            }
        }
        $registrosMapa = $query->orderBy('fecha', 'desc')->get();

        $data = $registrosMapa->filter(function ($registro) {
            return $registro->latitud !== null && $registro->longitud !== null;
        })->map(function ($registro) {
            return [
                'nro' => $registro->nro,
                'lat' => (float) $registro->latitud,
                'lng' => (float) $registro->longitud,
                'nombre' => $registro->nombre,
                'tel_cli' => $registro->tel_cli,
                'dia1' => $registro->dia1,
                'dia2' => $registro->dia2,
                'total' => number_format($registro->total, 2, ',', '.'),
                'direccion' => $registro->dir_cli,
                'yagestionado' => $registro->yagestionado,
           ];
        })->values()->toArray();

        return $data;
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function seleccionarMarcador($nro)
    {
        $this->mapaFiltroNro = $nro;
        $this->resetPage();
        if ($this->mostrarMapa) {
            $this->dispatchBrowserEvent('mapa-actualizado', [
                'markersData' => $this->markers_data,
                'primeraUbicacion' => $this->primeraUbicacion
            ]);
        }
    }

    public function limpiarFiltroMapa()
    {
        $this->mapaFiltroNro = null;
        $this->resetPage();
        if ($this->mostrarMapa) {
            $this->dispatchBrowserEvent('mapa-actualizado', [
                'markersData' => $this->markers_data,
                'primeraUbicacion' => $this->primeraUbicacion
            ]);
        }
    }

    public function updatingFiltroId()
    {
        $this->resetPage();
    }

    public function updatingNombre()
    {
        $this->resetPage();
    }

    public function mostrarDetalle($id)
    {
        $this->mensajeExito = null;
        $this->mensajeError = null;

        $emision = Emision::find($id);

        if (!$emision) {
            $this->mensajeError = 'Factura no encontrada';
            return;
        }

        $this->facturaSeleccionada = $emision;
        $this->modalDetalle = true;
    }

    public function cerrarModal()
    {
        $this->modalDetalle = false;
        $this->facturaSeleccionada = null;
        $this->detalleFactura = [];
    }

    public function solicitarMotivoBaja($id)
    {
        $this->emisionIdPendiente = $id;
        $this->tipoAccion = 'baja';
        $this->nombrecli = Emision::find($id)->nombre ?? 'Cliente desconocido';
        $this->motivoSeleccionado = null;
        $this->modalMotivo = true;
    }

    public function solicitarMotivoDevolucion($id)
    {
        $this->emisionIdPendiente = $id;
        $this->tipoAccion = 'devolucion';
        $this->nombrecli = Emision::find($id)->nombre ?? 'Cliente desconocido';
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

    public function solicitarMotivoBajaIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->solicitarMotivoBaja($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function solicitarMotivoDevolucionIndex($index)
    {
        if (isset($this->registrosIds[$index])) {
            $this->solicitarMotivoDevolucion($this->registrosIds[$index]);
        } else {
            $this->mensajeError = 'Registro no encontrado';
        }
    }

    public function generarPdfTicket80($datos)
    {

        $pdf = Pdf::loadView('pdf.ticket80', [
            'datos' => $datos
        ]);

        // tamaño real ticket 80mm
        //        $pdf->setPaper([0, 0, 226.77, 800]);
        //        $pdf->setPaper([0, 0, 210, 800]);
        $pdf->setPaper([0, 0, 226.77, 900]);
        $nombre = 'ticket_' . time() . '.pdf';

        $ruta = storage_path('app/public/' . $nombre);

        $pdf->save($ruta);

        return asset('storage/' . $nombre);
    }

    public function generarPdfTicket80Gfe($datos)
    {

        $pdf = Pdf::loadView('pdf.ticket80Gfe', [
            'datos' => $datos
        ]);

        // tamaño real ticket 80mm
        //        $pdf->setPaper([0, 0, 226.77, 800]);
        //        $pdf->setPaper([0, 0, 210, 800]);
        $pdf->setPaper([0, 0, 226.77, 900]);
        $nombre = 'ticket_' . time() . '.pdf';

        $ruta = storage_path('app/public/' . $nombre);

        $pdf->save($ruta);

        return asset('storage/' . $nombre);
    }

   }