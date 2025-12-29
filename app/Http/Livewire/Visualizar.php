<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use App\Models\Cob_filtro;
use App\Models\Emision;
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
    public $filtros, $filtro_id, $nombre, $telefono;
    public $mensajeExito;
    public $mensajeError;
    public $perPage = 15;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->filtros = Cob_filtro::all();
        $this->primeraUbicacion = $this->getFirstLocation();
    }

    public function cargarRegistros()
    {
        $query = Emision::whereNotNull('latitud')->where('nro_cobr', 680);

        if ($this->filtro_id == 2) {
            $query->where("apellidos", "like", "%" . trim($this->nombre) . "%");
        } else {
            if ($this->filtro_id == 4) {
                $query->where("tel_cli", "like", "%" . trim($this->telefono) . "%");
            } else {
                $fechad = $this->fechad ? date('Y-m-d', strtotime($this->fechad)) : null;
                $fechah = $this->fechah ? date('Y-m-d', strtotime($this->fechah)) : null;
                $fecha1 = substr($fechad, 8, 2);
                $fecha2 = substr($fechah, 8, 2);
                $query->where("dia1", ">=", $fecha1)->where("dia2", "<=", $fecha2);
                $this->nombre = '';
                $this->telefono = '';
            }
        }

        return $query->orderBy('fecha', 'desc');
    }

    public function getFirstLocation()
    {
        if ($this->filtro_id == 2) {
            $primerRegistro = Emision::where("apellidos", "like", "%" . trim($this->nombre) . "%")
                ->whereNotNull('latitud')->where('nro_cobr', 680)->first();
        } else {
            if ($this->filtro_id == 4) {
                $primerRegistro = Emision::where("tel_cli", "like", "%" . trim($this->telefono) . "%")
                    ->whereNotNull('latitud')->where('nro_cobr', 680)->first();
            } else {
                if ($this->filtro_id == 1) {
                    $fechad = $this->fechad ? date('Y-m-d', strtotime($this->fechad)) : null;
                    $fechah = $this->fechah ? date('Y-m-d', strtotime($this->fechah)) : null;
                    $fecha1 = substr($fechad, 8, 2);
                    $fecha2 = substr($fechah, 8, 2);
                    $primerRegistro = Emision::where("dia1", ">=", $fecha1)->where("dia2", "<=", $fecha2)
                        ->whereNotNull('latitud')->where('nro_cobr', 680)->first();
                } else {
                    $this->nombre = '';
                    $primerRegistro = Emision::whereNotNull('latitud')->where('nro_cobr', 680)
                        ->whereNotNull('longitud')
                        ->first();
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
                'lat' => -34.9011,
                'lng' => -56.1645
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
            'registros' => $registrosPaginados, // Pasar los registros paginados a la vista
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

        // No es necesario llamar a cargarRegistros() aquí, se hará en render()
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

        $emision->arqueo = 'B';
        $emision->save();
        $this->mensajeExito = 'Registro actualizado a Baja';
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

        $emision->arqueo = 'D';
        $emision->save();
        $this->mensajeExito = 'Registro actualizado a Devolución';
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

        // Aquí va la lógica para imprimir el ticket
        $this->mensajeExito = 'Ticket impreso correctamente';
    }

    public function aplicarFiltro()
    {
        // Resetear a la primera página al aplicar filtro
        $this->resetPage();

        // Si el mapa está visible, emitimos el evento
        if ($this->mostrarMapa) {
            $this->dispatchBrowserEvent('mapa-actualizado', [
                'markersData' => $this->markers_data,
                'primeraUbicacion' => $this->primeraUbicacion
            ]);
        }
    }

    public function getMarkersDataProperty()
    {
        // Obtener TODOS los registros para el mapa (no solo los de la página actual)
        $query = Emision::whereNotNull('latitud')->where('nro_cobr', 680);

        if ($this->filtro_id == 2) {
            $query->where("apellidos", "like", "%" . trim($this->nombre) . "%");
        } else {
            if ($this->filtro_id == 4) {
                $query->where("tel_cli", "like", "%" . trim($this->telefono) . "%");
            } else {
                $fechad = $this->fechad ? date('Y-m-d', strtotime($this->fechad)) : null;
                $fechah = $this->fechah ? date('Y-m-d', strtotime($this->fechah)) : null;
                $fecha1 = substr($fechad, 8, 2);
                $fecha2 = substr($fechah, 8, 2);
                $query->where("dia1", ">=", $fecha1)->where("dia2", "<=", $fecha2);
                $this->nombre = '';
                $this->telefono = '';
            }
        }

        $registrosMapa = $query->orderBy('fecha', 'desc')->get();

        $data = $registrosMapa->filter(function ($registro) {
            return $registro->latitud !== null && $registro->longitud !== null;
        })->map(function ($registro) {
            return [
                'lat' => (float) $registro->latitud,
                'lng' => (float) $registro->longitud,
                'apellidos' => $registro->apellidos,
                'tel_cli' => $registro->tel_cli,
                'dia1' => $registro->dia1,
                'dia2' => $registro->dia2,
                'total' => number_format($registro->total, 2, ',', '.'),
                'direccion' => $registro->dir_cli,
            ];
        })->values()->toArray();

        return $data;
    }

    public function updatingPerPage()
    {
        $this->resetPage(); // Resetear a la primera página al cambiar items por página
    }

    public function updatingFiltroId()
    {
        $this->resetPage(); // Resetear a la primera página al cambiar filtro
    }

    public function updatingNombre()
    {
        $this->resetPage(); // Resetear a la primera página al buscar
    }
}
