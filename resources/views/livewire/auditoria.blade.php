<div>
    @if ($mensajeError)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error!</strong> {{ $mensajeError }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if ($mensajeExito)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ $mensajeExito }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h4 style="color: blue">Auditar movimientos de un recibo</h4>

            <div class="row">
                <div class="col-lg-2 col-md-8">
                    <div class="form-group">
                        <input type="number" class="form-control" id="nrorec" name="nrorec"
                            placeholder="Escriba número" wire:model="nrorec" max="20">
                    </div>
                </div>
                <div class="col-lg-2 col-md-8">
                    <button type="button" id="btnProcesar" class="btn btn-success" data-toggle="tooltip"
                        data-placement="right" title="Procesar" wire:click="consultarRecibo"
                        wire:loading.attr="disabled">Procesar
                        <i class="fas fa-file-check"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- Tabla de Registros --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-responsive table-striped fs-6">
                        <thead class="bg-info">
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($registros as $registro)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($registro->fecha)) }}</td>
                                    <td>{{ $registro->hora }}</td>
                                    <td>{{ $registro->usuario }}</td>
                                    <td>{{ $registro->movimiento }}</td>
                                    <td>{{ $registro->obs }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No hay registros disponibles
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
