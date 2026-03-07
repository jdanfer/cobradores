<div>
    {{-- Mensajes de éxito y error --}}
    @if ($mensajeExito)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ $mensajeExito }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($mensajeError)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error!</strong> {{ $mensajeError }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <h4 style="color: blue">Registro de otras comisiones</h4>
                <div class="col-lg-4 col-md-8">
                    <div class="input-group mb-3">
                        <select id="cobrador_id" style="color: blue" class="form-control input-sm"
                            wire:model="cobrador_id" wire:loading.attr="disabled" name="cobrador_id">
                            <option value="">Seleccionar...</option>
                            @foreach ($cobradores as $cobrador)
                                @if (old('cobrador_id') == $cobrador->cod_sapp)
                                    <option value="{{ $cobrador->cod_sapp }}" selected>
                                        {{ $cobrador->name }}
                                    </option>
                                @else
                                    <option value="{{ $cobrador->cod_sapp }}">{{ $cobrador->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Registros --}}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-8">
                    @if ($cobrador_id != null)
                        <button type="button" id="btnNuevo" class="btn btn-success" data-toggle="tooltip"
                            data-placement="right" title="Ingresar nuevo" wire:click="ingresarEntrega"
                            wire:loading.attr="disabled">Nuevo registro
                            <i class="fas fa-file"></i>
                        </button>
                    @else
                        <button type="button" id="btnNuevo" class="btn btn-success" data-toggle="tooltip"
                            data-placement="right" title="Ingresar nuevo" disabled wire:click="ingresarEntrega"
                            wire:loading.attr="disabled">Nuevo registro
                            <i class="fas fa-file"></i>
                        </button>
                    @endif
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-sm table-responsive table-striped fs-6">
                    <thead class="bg-info">
                        <tr>
                            <th>Fecha</th>
                            <th>Cobrador</th>
                            <th>Observación</th>
                            <th>Importe $</th>
                            <th>Editar</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entregas as $index => $entrega)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($entrega->fecha)) }}</td>
                                <td>{{ $entrega->cobrador }}</td>
                                <td style="width: 350px">{{ $entrega->obs }}</td>
                                <td>${{ number_format($entrega->pesos, 2, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                        data-placement="right" title="Editar"
                                        wire:click.prevent="editarEntrega({{ $entrega->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip"
                                        data-placement="right" title="Eliminar"
                                        wire:click="borrarEntrega({{ $entrega->id }})" wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay registros disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Mostrando {{ $entregas->firstItem() }} - {{ $entregas->lastItem() }} de
                        {{ $entregas->total() }} registros
                    </div>

                    {{ $entregas->links() }}

                    <div class="form-inline">
                        <label for="perPage" class="mr-2">Por página:</label>
                        <select id="perPage" class="form-control form-control-sm" wire:model="perPage">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Edición --}}
    @if ($showEditModal)
        <div class="modal fade show" id="editModal" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Editar registro</h5>
                        <button type="button" class="close text-white" wire:click="cerrarModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="importe">Importe <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('importe') is-invalid @enderror"
                                    id="importe" wire:model.defer="importe" step="0.01" min="0"
                                    placeholder="Ingrese el importe">
                                @error('importe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="obs">Observación</label>
                                <textarea class="form-control @error('obs') is-invalid @enderror" id="obs" wire:model.defer="obs"
                                    rows="3" maxlength="200" placeholder="Ingrese una observación (opcional)"></textarea>
                                @error('obs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Máximo 200 caracteres</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="actualizarEntrega">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts para confirmación de eliminación --}}
    <script>
        document.addEventListener('livewire:load', function() {
            // Escuchar el evento de confirmación de borrado
            Livewire.on('confirmar-borrar-comision', entregaId => {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: "¿Desea eliminar este registro? Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('eliminarEntrega', entregaId);
                    }
                });
            });

            // Auto-cerrar mensajes después de 5 segundos
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
    {{-- Modal de Creación --}}
    @if ($showCreateModal)
        <div class="modal fade show" id="createModal" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Nuevo registro</h5>
                        <button type="button" class="close text-white" wire:click="cerrarModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" wire:model.defer="fecha">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hora</label>
                                        <input type="time" class="form-control" wire:model.defer="hora">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Importe <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control @error('importe') is-invalid @enderror"
                                        wire:model.defer="importe" step="0.01">
                                </div>
                                @error('importe')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Observación</label>
                                <textarea class="form-control" maxlength="200" wire:model.defer="obs" rows="2"></textarea>
                                <small class="form-text text-muted">Máximo 200 caracteres</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                        <button type="button" class="btn btn-success" wire:click="guardarEntrega">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
