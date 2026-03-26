<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Cob_mutual;
use App\Models\Abm_arqueo;
use App\Models\Cliente;
use App\Models\Cob_comision;
use App\Models\Cob_entrega;
use App\Models\Cob_inf;
use App\Models\Cob_mutuales;
use App\Models\Emi0725;
use App\Models\Deuda;
use App\Models\Emision;
use App\Models\Linmmdd;
use App\Models\User;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

class InformesCobrador extends Component
{
    public $seleccion_cob;
    public $detalle, $totalCobrado, $totalEntregado, $totalDif, $totalComision, $totalMutual;
    public $cobradores, $cobrador_id, $totalVigilia, $totalGeneral, $historico, $mes, $anio;
    public function mount()
    {
        $this->cobradores = User::whereIn('escobrador', [1])->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.informes-cobrador', [
            'cobradores' => $this->cobradores,
        ]);
    }

    public function informeCobrador()
    {
        if (auth()->user()->hcerol_id != 1) {
            $this->cobrador_id = auth()->user()->cod_sapp;
        } else {
            $this->cobrador_id = $this->cobrador_id;
        }
        if ($this->seleccion_cob == null) {
            session()->flash('messageerror', 'Debe seleccionar un tipo de informe para emitir.');
        } else {
            if ($this->cobrador_id == null) {
                session()->flash('messageerror', 'Debe seleccionar un cobrador para emitir el informe.');
            } else {
                if ($this->seleccion_cob == "Pendientes") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Inf.Pendientes";
                    $abminf->save();
                    $registros = Emision::where('arqueo', 'P')
                        ->where('cob', $this->cobrador_id)->orderBy('color')->orderBy('nrorec')
                        ->get()
                        ->groupBy('color');
                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-detalle-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-informes-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'pendientes_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros pendientes.');
                    }
                }
                if ($this->seleccion_cob == "Devoluciones") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Inf.Devoluciones";
                    $abminf->save();

                    $registros = Emision::whereIn('arqueo', ['D', 'W'])
                        ->where('cob', $this->cobrador_id)->orderBy('color')->orderBy('nrorec')
                        ->get()
                        ->groupBy('color');

                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-detalle-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-informes-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'devoluciones_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros devoluciones.');
                    }
                }
                if ($this->seleccion_cob == "Bajas") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Inf.Bajas";
                    $abminf->save();
                    $registros = Emision::whereIn('arqueo', ['B', 'V'])
                        ->where('cob', $this->cobrador_id)->orderBy('color')->orderBy('nrorec')
                        ->get()
                        ->groupBy('color');
                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-detalle-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-informes-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'bajas_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros bajas.');
                    }
                }
                if ($this->seleccion_cob == "Cobrados") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Inf.Cobrados";
                    $abminf->save();
                    $registros = Emision::whereIn('arqueo', ['C'])
                        ->where('cob', $this->cobrador_id)->orderBy('color')->orderBy('nrorec')
                        ->get()
                        ->groupBy('color');

                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-detalle-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-informes-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'cobrados_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros cobrados.');
                    }
                }
                if ($this->seleccion_cob == "Resumen") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Inf.Resumen";
                    $abminf->save();

                    if ($this->historico) {
                        if ($this->mes < 10) {
                            $meshistorico = "arq" . "0" . $this->mes . substr($this->anio, 2, 2);
                        } else {
                            $meshistorico = "arq" . $this->mes . substr($this->anio, 2, 2);
                        }
                    } else {
                        $meshistorico = null;
                    }
                    if ($meshistorico) {
                        $elmes = (new Emi0725)
                            ->setTable($meshistorico)
                            ->where('cob', $this->cobrador_id)->first();
                        $this->totalMutual = Cob_mutuales::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->whereNull('vigilia')->sum('pesos');
                        $this->totalVigilia = Cob_mutuales::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->whereIn('vigilia', [1])->sum('pesos');
                        $totalOtras = Cob_comision::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->sum('pesos');
                        $this->totalCobrado = (new Emi0725)
                            ->setTable($meshistorico)
                            ->where('cob', $this->cobrador_id)
                            ->where('arqueo', 'C')->sum('total');
                        $this->totalEntregado = Cob_entrega::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->whereIn('valida', [1])
                            ->sum('pesos');
                        $this->totalGeneral = $this->totalCobrado + $this->totalMutual + $this->totalVigilia;
                        $this->totalDif = $this->totalEntregado - $this->totalGeneral;
                        $totalComision = $this->totalCobrado * 0.07;
                        $totalComisionMutual = $this->totalMutual * 0.0315;
                        $totalComisionVigilia = $this->totalVigilia * 0.0315;
                        $totalComisionTot = $totalComision + $totalComisionMutual + $totalComisionVigilia + $totalOtras;
                        $nombrecobrador = User::where('cod_sapp', $this->cobrador_id)->first();
                        $pdf = PDF::loadView('livewire.reporte-resumen-cob', [
                            'totalCobrado' => $this->totalCobrado,
                            'totalMutual' => $this->totalMutual,
                            'totalVigilia' => $this->totalVigilia,
                            'totalGeneral' => $this->totalGeneral,
                            'totalEntregado' => $this->totalEntregado,
                            'totalDif' => $this->totalDif,
                            'totalComision' => $totalComision,
                            'seleccion_cob' => $this->seleccion_cob,
                            'cobradornro' => $this->cobrador_id,
                            'totalTotComision' => $totalComisionTot,
                            'totalcomisionmutual' => $totalComisionMutual,
                            'totalcomisionvigilia' => $totalComisionVigilia,
                            'totalOtras' => $totalOtras,
                            'nombrecobrador' => $nombrecobrador->name,
                        ])->setPaper('a4', 'portrait');
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'resumen_' . auth()->user()->id . '.pdf');
                    } else {
                        $elmes = Emision::where('cob', $this->cobrador_id)
                            ->orderBy('mesarq', 'desc')
                            ->first();
                        $this->totalMutual = Cob_mutuales::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->whereNull('vigilia')->sum('pesos');
                        $this->totalVigilia = Cob_mutuales::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->whereIn('vigilia', [1])->sum('pesos');
                        $totalOtras = Cob_comision::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->sum('pesos');
                        $this->totalCobrado = Emision::where('cob', $this->cobrador_id)
                            ->where('arqueo', 'C')->sum('total');
                        $this->totalEntregado = Cob_entrega::where('cobrador', $this->cobrador_id)->where('arqueo', $elmes->mesarq)->whereIn('valida', [1])
                            ->sum('pesos');
                        $this->totalGeneral = $this->totalCobrado + $this->totalMutual + $this->totalVigilia;
                        $this->totalDif = $this->totalEntregado - $this->totalGeneral;
                        $totalComision = $this->totalCobrado * 0.07;
                        $totalComisionMutual = $this->totalMutual * 0.0315;
                        $totalComisionVigilia = $this->totalVigilia * 0.0315;
                        $totalComisionTot = $totalComision + $totalComisionMutual + $totalComisionVigilia + $totalOtras;
                        $nombrecobrador = User::where('cod_sapp', $this->cobrador_id)->first();
                        $pdf = PDF::loadView('livewire.reporte-resumen-cob', [
                            'totalCobrado' => $this->totalCobrado,
                            'totalMutual' => $this->totalMutual,
                            'totalVigilia' => $this->totalVigilia,
                            'totalGeneral' => $this->totalGeneral,
                            'totalEntregado' => $this->totalEntregado,
                            'totalDif' => $this->totalDif,
                            'totalComision' => $totalComision,
                            'seleccion_cob' => $this->seleccion_cob,
                            'cobradornro' => $this->cobrador_id,
                            'totalTotComision' => $totalComisionTot,
                            'totalcomisionmutual' => $totalComisionMutual,
                            'totalcomisionvigilia' => $totalComisionVigilia,
                            'totalOtras' => $totalOtras,
                            'nombrecobrador' => $nombrecobrador->name,
                        ])->setPaper('a4', 'portrait');
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'resumen_' . auth()->user()->id . '.pdf');
                    }
                }
                if ($this->seleccion_cob == "Reimpresion") {
                    $registros = Emision::whereNotNull('reimp')
                        ->where('cob', $this->cobrador_id)->orderBy('color')->orderBy('nrorec')
                        ->get()
                        ->groupBy('color');
                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-detalle-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-informes-cob', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'reimpresiones_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros de reimpresiones.');
                    }
                }
                if ($this->seleccion_cob == "Sinemision") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Sin emisión";
                    $abminf->save();

                    if (date('n') == 1) {
                        $mes = 12;
                        $anio = date('Y') - 1;
                    } else {
                        $mes = date('n') - 1;
                        $anio = date('Y');
                    }
                    if ($mes < 10) {
                        $arq = "arq" . "0" . $mes . substr($anio, 2, 2);
                    } else {
                        $arq = "arq" . $mes . substr($anio, 2, 2);
                    }

                    //                    $modelo = new Emi0725();
                    $nombrear = $arq;
                    //                    $modelo->setTable($nombrear);
                    $seleccion = (new Emi0725)
                        ->setTable($nombrear)
                        ->where('cob', $this->cobrador_id)->whereIn('arqueo', ['C', 'P', 'D'])
                        ->get();
                    //                    $seleccion = $modelo::where('cob', $this->cobrador_id)->get();
                    $borrar = Cob_inf::where('id', '>', 0)->delete();
                    foreach ($seleccion as $registro) {
                        $existe = Emision::where('matricula', $registro->matricula)->first();
                        if (!$existe) {
                            $cliente = Cliente::where('CL_CODIGO', $registro->matricula)->first();
                            if ($cliente) {
                                if ($cliente->FECHA_BAJA != null) {
                                    if ($cliente->CL_NROCOBR == $registro->cob) {
                                        $obs = $cliente->CL_CODCONV;
                                    } else {
                                        $obs = "Otro COBRADOR";
                                    }
                                } else {
                                    $obs = "BAJA";
                                }
                            } else {
                                $obs = "NO ENCONTRADO";
                            }
                            $agregoalinforme = new Cob_inf();
                            $agregoalinforme->fecha = date('Y-m-d');
                            $agregoalinforme->matricula = $registro->matricula;
                            $agregoalinforme->cob = $registro->cob;
                            $agregoalinforme->nombre = $registro->nombre;
                            $agregoalinforme->categ = $registro->cat;
                            $agregoalinforme->total = $registro->total;
                            $agregoalinforme->nrorec = $registro->nrorec;
                            $agregoalinforme->direccion = $registro->dir_cli;
                            $agregoalinforme->color = $registro->color;
                            $agregoalinforme->usuario_ced = $registro->usuario_ced;
                            $agregoalinforme->obs = $obs;
                            $agregoalinforme->save();
                        }
                    }
                    $registros = Cob_inf::all();
                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-sinemi', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-sinemi', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'sinemitir_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros.');
                    }
                }
                if ($this->seleccion_cob == "Nuevos") {
                    $abminf = new Abm_arqueo();
                    $abminf->fecha = date('Y-m-d');
                    $abminf->hora = date('H:i');
                    $abminf->usuario = auth()->user()->name;
                    $abminf->base = 99;
                    $abminf->movimiento = "I";
                    $abminf->obs = "Inf.Nuevos";
                    $abminf->save();

                    if (date('n') == 1) {
                        $mes = 12;
                        $anio = date('Y') - 1;
                    } else {
                        $mes = date('n') - 1;
                        $anio = date('Y');
                    }
                    if ($mes < 10) {
                        $arq = "arq" . "0" . $mes . substr($anio, 2, 2);
                    } else {
                        $arq = "arq" . $mes . substr($anio, 2, 2);
                    }

                    //                    $modelo = new Emi0725();
                    $nombrear = $arq;
                    //                    $modelo->setTable($nombrear);
                    //                    $seleccionant = (new Emi0725)
                    //                        ->setTable($nombrear)
                    //                        ->where('cob', $this->cobrador_id)->whereIn('arqueo', ['C', 'P', 'D'])
                    //                        ->get();
                    //                    $seleccion = $modelo::where('cob', $this->cobrador_id)->get();
                    $borrar = Cob_inf::where('id', '>', 0)->delete();
                    $seleccion = Emision::where('cob', $this->cobrador_id)->whereIn('arqueo', ['C', 'P'])->get();
                    foreach ($seleccion as $registro) {
                        $existe = (new Emi0725)
                            ->setTable($nombrear)
                            ->where('matricula', $registro->matricula)->first();
                        if (!$existe) {
                            $agregoalinforme = new Cob_inf();
                            $agregoalinforme->fecha = date('Y-m-d');
                            $agregoalinforme->matricula = $registro->matricula;
                            $agregoalinforme->cob = $registro->cob;
                            $agregoalinforme->nombre = $registro->nombre;
                            $agregoalinforme->categ = $registro->cat;
                            $agregoalinforme->total = $registro->total;
                            $agregoalinforme->nrorec = $registro->nrorec;
                            $agregoalinforme->direccion = $registro->dir_cli;
                            $agregoalinforme->color = $registro->color;
                            $agregoalinforme->usuario_ced = $registro->usuario_ced;
                            $agregoalinforme->save();
                        }
                    }
                    $registros = Cob_inf::all();
                    if ($registros->count() > 0) {
                        if ($this->detalle) {
                            $pdf = PDF::loadView('livewire.reporte-coneminueva', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        } else {
                            $pdf = PDF::loadView('livewire.reporte-coneminueva', [
                                'registros' => $registros,
                                'seleccion_cob' => $this->seleccion_cob,
                                'cobradornro' => $this->cobrador_id,
                            ])->setPaper('a4', 'portrait');
                        }
                        // Para descargar directamente
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'coneminueva_' . auth()->user()->id . '.pdf');
                    } else {
                        session()->flash('messageerror', 'No hay registros.');
                    }
                }
            }
        }
    }

    public function procesarExcel()
    {
        $fechahoy = date("Y-m-d");
        $fechadesde = date("Y-m-d", strtotime($fechahoy . "- 30 days"));

        if (auth()->user()->hcerol_id != 1) {
            $this->cobrador_id = auth()->user()->cod_sapp;
        } else {
            $this->cobrador_id = $this->cobrador_id;
        }
        if ($this->seleccion_cob == "historial") {
            $registros = Abm_arqueo::where('movimiento', 'I')->where('fecha', '>=', $fechadesde)->get();
            if ($registros->count() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Establecer encabezados
                $sheet->setCellValue('C1', 'Informe de Historial de Informes');

                $sheet->setCellValue('A2', 'Fecha');
                $sheet->setCellValue('B2', 'Hora');
                $sheet->setCellValue('C2', 'Usuario');
                $sheet->setCellValue('D2', 'Acción');

                // Estilo para encabezados (opcional)
                $sheet->getStyle('C1')->getFont()->setBold(true);

                $sheet->getStyle('A2:D2')->getFont()->setBold(true);

                // Llenar datos
                $row = 3;
                foreach ($registros as $registro) {
                    $sheet->setCellValue('A' . $row, date('d-m-Y', strtotime($registro->fecha)));
                    $sheet->setCellValue('B' . $row, $registro->hora);
                    $sheet->setCellValue('C' . $row, $registro->usuario);
                    $sheet->setCellValue('D' . $row, $registro->obs);
                    $row++;
                }

                // Ajustar ancho de columnas automáticamente
                foreach (range('A', 'D') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Generar archivo
                $writer = new WriterXlsx($spreadsheet);
                $filename = 'historial_' . date('YmdHis') . '.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $filename);

                $writer->save($temp_file);

                return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
            } else {
                session()->flash('messageerror', 'No hay registros cobrados.');
            }
        }

        if ($this->seleccion_cob == "porcentajes") {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $meshoy = Emision::where('nro', '>', 0)->first();
            $elmesarq = $meshoy->mesarq;
            if (strlen($elmesarq) == 7) {
                $mes = substr($elmesarq, 0, 2);
                $anio = substr($elmesarq, 3, 4);
            } else {
                $mes = substr($elmesarq, 0, 1);
                $anio = substr($elmesarq, 2, 4);
            }
            // Establecer encabezados
            if ($mes < 10) {
                $fechaentexto = "01" . "/0" . $mes . "/" . $anio;
            }
            $fechainicial = new DateTime($fechaentexto);
            $fechafin = new DateTime($fechaentexto);
            $fechafin->modify('last day of this month');

            $sheet->getStyle('A1:E1')->getFont()->setBold(true);

            $sheet->setCellValue('B1', 'DEPARTAMENTO TI SAPP S.A.');
            $sheet->setCellValue('E1', 'Fecha: ' . date('d-m-Y'));

            $sheet->getStyle('A2:E2')->getFont()->setBold(true);

            $sheet->setCellValue('B2', 'Informe de porcentaje de cobranza por cobrador');
            $sheet->setCellValue('E2', 'ARQUEO ' . $mes . '-' . $anio);

            $sheet->getStyle('A4:F4')->getFont()->setBold(true);
            $sheet->setCellValue('A4', 'Número');
            $sheet->setCellValue('B4', 'Nombre');
            $sheet->setCellValue('C4', 'Cobrado $.');
            $sheet->setCellValue('D4', 'Tot.Recibos');
            $sheet->setCellValue('E4', 'Rec.Base');
            $sheet->setCellValue('F4', '$. Base');
            $sheet->setCellValue('G4', 'Rec.RedPagos');
            $sheet->setCellValue('H4', '$. Redpagos');
            $sheet->setCellValue('I4', 'Rec.Sistarbanc');
            $sheet->setCellValue('J4', '$. Sistarbanc');

            $sheet->setCellValue('K4', '% Cobrador');
            $sheet->setCellValue('L4', 'Tot.Emisión');
            $sheet->setCellValue('M4', '% Cobrador $');
            $sheet->setCellValue('N4', '% Total Cant.');
            $sheet->setCellValue('O4', '% Total $.');

            $loscobradores = User::whereIn('escobrador', [1])->whereNotIn('hcerol_id', [1])->orderBy('name')->get();
            $row = 5;
            foreach ($loscobradores as $cobrador) {
                $totalCobrado = Emision::where('cob', $cobrador->cod_sapp)->where('arqueo', 'C')->where('mes', $mes)->where('ano', $anio)->whereNull('pagoenrp')->whereNull('pagoensist')->sum('total');
                $cantidadRecibos = Emision::where('cob', $cobrador->cod_sapp)->where('arqueo', 'C')->where('mes', $mes)->where('ano', $anio)->whereNull('pagoenrp')->whereNull('pagoensist')->count();
                $totalemision = Emision::where('cob', $cobrador->cod_sapp)->where('mes', $mes)->where('ano', $anio)->count();
                $totalemisionPesos = Emision::where('cob', $cobrador->cod_sapp)->where('mes', $mes)->where('ano', $anio)->sum('total');
                $totalcantbasecob = Linmmdd::where('fecha', '>=', $fechainicial)->where('fecha', '<=', $fechafin)->where('cod_prod', 999)->where('GRUPO', $cobrador->cod_sapp)->where('MES_PAGA', $mes)->where('ANO_PAGA', $anio)->count();
                $totalpesosbasecob = Linmmdd::where('fecha', '>=', $fechainicial)->where('fecha', '<=', $fechafin)->where('cod_prod', 999)->where('GRUPO', $cobrador->cod_sapp)->where('MES_PAGA', $mes)->where('ANO_PAGA', $anio)->sum('TOT_LIN');
                $totalcantredpagos = Emision::where('cob', $cobrador->cod_sapp)->where('mes', $mes)->where('ano', $anio)->whereNotNull('pagoenrp')->count();
                $totalpesosredpagos = Emision::where('cob', $cobrador->cod_sapp)->where('mes', $mes)->where('ano', $anio)->whereNotNull('pagoenrp')->sum('total');
                $totalcantsistar = Emision::where('cob', $cobrador->cod_sapp)->where('mes', $mes)->where('ano', $anio)->whereNotNull('pagoensist')->count();
                $totalpesossistar = Emision::where('cob', $cobrador->cod_sapp)->where('mes', $mes)->where('ano', $anio)->whereNotNull('pagoensist')->sum('total');

                if ($totalemision > 0) {
                    $cantidadRecibos = $cantidadRecibos - $totalcantbasecob - $totalcantredpagos - $totalcantsistar;
                    $totalcobradoPesos = $totalCobrado - $totalpesosbasecob - $totalpesosredpagos - $totalpesossistar; 
                    $porcentaje = ($cantidadRecibos / ($totalemision * 100)) * 100;
                    $porcentajePesos = ($totalcobradoPesos / ($totalemisionPesos * 100)) * 100;
                    $cantidadTotalRec = $cantidadRecibos;
                    $totalPesosTotal = $totalcobradoPesos;
                    $porcentajeTotalTot = ($cantidadTotalRec / ($totalemision * 100)) * 100;
                    $porcentajePesosTot = ($totalPesosTotal / ($totalemisionPesos * 100)) * 100;
                } else {
                    $porcentaje = 0;
                    $porcentajePesos = 0;
                    $porcentajeTotalTot = 0;
                    $porcentajePesosTot = 0;
                }
                $sheet->setCellValue('A' . $row, $cobrador->cod_sapp);
                $sheet->setCellValue('B' . $row, $cobrador->name);
                $sheet->setCellValue('C' . $row, number_format($totalCobrado, 2, ',', '.'));
                $sheet->setCellValue('D' . $row, $cantidadRecibos);
                $sheet->setCellValue('E' . $row, $totalcantbasecob);
                $sheet->setCellValue('F' . $row, number_format($totalpesosbasecob, 2, ',', '.'));
                $sheet->setCellValue('G' . $row, $totalcantredpagos);
                $sheet->setCellValue('H' . $row, number_format($totalpesosredpagos, 2, ',', '.'));
                $sheet->setCellValue('I' . $row, $totalcantsistar);
                $sheet->setCellValue('J' . $row, number_format($totalpesossistar, 2, ',', '.'));
                $sheet->setCellValue('K' . $row, number_format($porcentaje, 2, ',', '.') . '%');
                $sheet->setCellValue('L' . $row, $totalemision);
                $sheet->setCellValue('M' . $row, number_format($porcentajePesos, 2, ',', '.') . '%');
                $sheet->setCellValue('N' . $row, number_format($porcentajeTotalTot, 2, ',', '.') . '%');
                $sheet->setCellValue('O' . $row, number_format($porcentajePesosTot, 2, ',', '.') . '%');
                $row++;
            }

            // Ajustar ancho de columnas automáticamente
            foreach (range('A', 'O') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Generar archivo
            $writer = new WriterXlsx($spreadsheet);
            $filename = 'porcentajes_' . date('YmdHis') . '.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $filename);

            $writer->save($temp_file);

            return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
        }

        if ($this->seleccion_cob == "Cobrados") {
            $registros = Emision::whereIn('arqueo', ['C'])
                ->where('cob', $this->cobrador_id)->orderBy('color')->orderBy('nrorec')
                ->get()
                ->groupBy('color');

            if ($registros->count() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Establecer encabezados
                $sheet->setCellValue('A1', 'Fecha');
                $sheet->setCellValue('B1', 'Matricula');
                $sheet->setCellValue('C1', 'Recibo');
                $sheet->setCellValue('D1', 'Nombre');
                $sheet->setCellValue('E1', 'Cédula');
                $sheet->setCellValue('F1', 'Color');

                // Estilo para encabezados (opcional)
                $sheet->getStyle('A1:F1')->getFont()->setBold(true);

                // Llenar datos
                $row = 2;
                foreach ($registros as $color => $grupo) {
                    foreach ($grupo as $registro) {
                        $sheet->setCellValue('A' . $row, date('d-m-Y', strtotime($registro->fecha)));
                        $sheet->setCellValue('B' . $row, $registro->matricula);
                        $sheet->setCellValue('C' . $row, $registro->nrorec);
                        $sheet->setCellValue('D' . $row, $registro->nombre);
                        $sheet->setCellValue('E' . $row, $registro->usuario_ced);
                        $sheet->setCellValue('F' . $row, $registro->color);
                        $row++;
                    }
                }

                // Ajustar ancho de columnas automáticamente
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Generar archivo
                $writer = new WriterXlsx($spreadsheet);
                $filename = 'cobrados_' . date('YmdHis') . '.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $filename);

                $writer->save($temp_file);

                return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
            } else {
                session()->flash('messageerror', 'No hay registros cobrados.');
            }
        }

        if ($this->seleccion_cob == "devolcobranza") {
            $registros = Deuda::where('desdeapp', 'Devolución Cobranza')->where('fecha_anula', '>=', $fechadesde)->get();
            if ($registros->count() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Establecer encabezados
                $sheet->setCellValue('C1', 'Informe devolución de cobranza');

                $sheet->setCellValue('A2', 'Fecha');
                $sheet->setCellValue('B2', 'Matrícula');
                $sheet->setCellValue('C2', 'Nombre');
                $sheet->setCellValue('D2', 'Importe');
                $sheet->setCellValue('E2', 'Nro.Documento');
                $sheet->setCellValue('F2', 'Cobrador');

                // Estilo para encabezados (opcional)
                $sheet->getStyle('C1')->getFont()->setBold(true);

                $sheet->getStyle('A2:F2')->getFont()->setBold(true);

                // Llenar datos
                $row = 3;
                foreach ($registros as $registro) {
                    $sheet->setCellValue('A' . $row, date('d-m-Y', strtotime($registro->fecha_anula)));
                    $sheet->setCellValue('B' . $row, $registro->CLIENTE);
                    $sheet->setCellValue('C' . $row, $registro->nombre);
                    $sheet->setCellValue('D' . $row, $registro->TOTAL);
                    $sheet->setCellValue('E' . $row, $registro->DOCUMENTO);
                    $sheet->setCellValue('F' . $row, $registro->NRO_COBR);
                    $row++;
                }

                // Ajustar ancho de columnas automáticamente
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Generar archivo
                $writer = new WriterXlsx($spreadsheet);
                $filename = 'anulaciones_' . date('YmdHis') . '.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $filename);

                $writer->save($temp_file);

                return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
            } else {
                session()->flash('messageerror', 'No hay registros.');
            }
        }

        //        return Excel::download(
        //            new HceenfermeriamedsExport($agendas, $fechadesde, $fechahasta, $base, $norealizahce),
        //            'ReporteEnf.xlsx'
        //        );
    }
}