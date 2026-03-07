<div>
    <div class="card">
        <div class="card-body">
            @if (auth()->user()->hcerol_id == 1)
                <div class="row">
                    <div class="col-lg-2 col-md-8">
                        <label style="color: blue" for="cobrador_select">
                            Seleccione cobrador:
                        </label>
                    </div>
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
            @else
                <h4 style="color: blue">Resumen cobranza de: {{ auth()->user()->name }}</h4>
            @endif
            <div class="row">
                <div class="col-lg-1 col-md-8">
                    <label style="color: blue" for="filtro">
                        Búsqueda:
                    </label>
                </div>
                <div class="col-lg-2 col-md-8">
                    <div class="input-group mb-3">
                        <select id="filtro_id" style="color: blue" class="form-control input-sm" wire:model="filtro_id"
                            name="filtro_id">
                            <option value="">Seleccionar...</option>
                            @foreach ($filtros as $filtro)
                                @if (old('filtro_id') == $filtro->id)
                                    <option value="{{ $filtro->id }}" selected>
                                        {{ $filtro->descrip }}
                                    </option>
                                @else
                                    <option value="{{ $filtro->id }}">{{ $filtro->descrip }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="grupof" wire:model="grupof"
                            id="grupof">
                        <label class="custom-control-label" style="color: blue; font-size: 15px" for="grupof">Ordenar
                            por Grupo Fliar</label>
                    </div>
                </div>
                @if ($filtro_id == 1)
                    <div class="col-lg-2 col-md-8">
                        <div class="form-group">
                            <input type="date" class="form-control" id="fechad" name="fechad" wire:model="fechad"
                                value="{{ old('fechad') }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-8">
                        <div class="form-group">
                            <input type="date" class="form-control" id="fechah" name="fechah" wire:model="fechah"
                                value="{{ old('fechah') }}">
                        </div>
                    </div>
                @endif
                @if ($filtro_id == 2 || $filtro_id == 7 || $filtro_id == 8)
                    <div class="col-lg-4 col-md-8">
                        <div class="form-group">
                            @if ($filtro_id == 2)
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    placeholder="Escriba nombre..." wire:model="nombre" max="30">
                            @endif
                            @if ($filtro_id == 7)
                                <input type="text" class="form-control" id="cedula" name="cedula"
                                    placeholder="Escriba cédula..." wire:model="cedula" max="30">
                            @endif
                            @if ($filtro_id == 8)
                                <input type="text" class="form-control" id="edocumento" name="edocumento"
                                    placeholder="Escriba documento..." wire:model="edocumento" max="30">
                            @endif
                            @if ($filtro_id == 4)
                                <input type="text" class="form-control" id="telefono"
                                    placeholder="Escriba teléfono..." name="telefono" wire:model="telefono"
                                    max="30">
                            @endif

                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Tabla de Registros --}}
    <div class="card">
        <div class="card-body">
            <label style="color: blue; font-size: 30px;" for="totalcob">Total cobrado
                $:{{ number_format($sumatotal, 2, ',', '.') }}</label>
            <div class="table-responsive">
                <table class="table table-sm table-responsive table-striped fs-6">
                    <thead class="bg-info">
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Total$</th>
                            <th>Origen</th>
                            @if ($filtro_id == 7)
                                <th>Cédula</th>
                            @elseif ($filtro_id == 8)
                                <th>Documento</th>
                            @else
                                <th>Teléfono</th>
                            @endif
                            <th>GrupoFliar</th>
                            <th>Anular</th>
                            <th>Reimprimir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $index => $registro)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($registro->fecha)) }}</td>
                                <td>{{ $registro->nombre }}</td>
                                <td>${{ number_format($registro->total, 2, ',', '.') }}</td>
                                <td>{{ $registro->origen }}</td>
                                @if ($filtro_id == 7)
                                    <td>{{ $registro->usuario_ced }}</td>
                                @elseif ($filtro_id == 8)
                                    <td>{{ $registro->nrorec }}</td>
                                @else
                                    <td>{{ $registro->tel_cli }}</td>
                                @endif
                                <td>{{ $registro->grupof }}</td>
                                <td>
                                    <button type="button" id="btnDevol" class="btn btn-danger btn-sm"
                                        data-toggle="tooltip" data-placement="right" title="Devolución"
                                        wire:loading.attr="disabled"
                                        wire:click="solicitarMotivoDevolucionIndex({{ $index }})"><i
                                            class="fas fa-reply"></i>
                                    </button>
                                </td>
                                <td>
                                    <button type="button" id="btnCancelaTck" class="btn btn-primary btn-sm"
                                        data-toggle="tooltip" data-placement="right" title="Re-Emitir ticket"
                                        wire:click="confirmarImpresionIndex({{ $index }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Mostrando {{ $registros->firstItem() }} - {{ $registros->lastItem() }} de
                        {{ $registros->total() }} registros
                    </div>

                    {{ $registros->links() }}

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

    <script>
        window.addEventListener('imprime-cobro', event => {
            Swal.fire({
                title: 'Confirma re-impresión?',
                html: "Verifique si la impresora está lista.",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, re-imprimir.',
            }).then((result) => {
                if (result.value) {
                    @this.call('imprimirTicket', event.detail.id)
                }
            });
        });
        window.addEventListener('confirmar-baja', event => {
            Swal.fire({
                title: 'Confirma BAJA?',
                html: "No podrá revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, BAJA',
            }).then((result) => {
                if (result.value) {
                    @this.call('guardarArqueoB', event.detail.id)
                }
            });
        });

        window.addEventListener('confirmar-devol', event => {
            Swal.fire({
                title: 'Confirma pasar a pendiente?',
                html: "No podrá revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, pasar a pendiente',
            }).then((result) => {
                if (result.value) {
                    @this.call('guardarArqueoD', event.detail.id)
                }
            });
        });

        document.addEventListener('livewire:load', function() {
            // Inicializar tooltips de Bootstrap
            $('[data-toggle="tooltip"]').tooltip();

            // Recargar tooltips cuando Livewire actualice el DOM
            Livewire.hook('message.processed', () => {
                $('[data-toggle="tooltip"]').tooltip();
            });

            // Manejar mensajes
            Livewire.hook('message.processed', (message, component) => {
                // Desaparecer mensaje de éxito después de 3 segundos
                if (document.getElementById('mensajeExito')) {
                    setTimeout(function() {
                        const alert = document.getElementById('mensajeExito');
                        if (alert) {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(function() {
                                @this.set('mensajeExito', null);
                            }, 500);
                        }
                    }, 3000);
                }

                // Desaparecer mensaje de error después de 5 segundos
                if (document.getElementById('mensajeError')) {
                    setTimeout(function() {
                        const alert = document.getElementById('mensajeError');
                        if (alert) {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(function() {
                                @this.set('mensajeError', null);
                            }, 500);
                        }
                    }, 5000);
                }
            });
        });

        // Listener para imprimir en impresora térmica
        window.addEventListener('imprimir-ticket-arq', event => {
            imprimirTicketArqueo(event.detail.datos);
        });

        function imprimirTicketArqueo(datos) {
            // Crear el contenido del ticket para impresora térmica 80mm
            const contenido = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    @page {
                        size: 80mm auto;
                        margin: 0;
                    }
                    body {
                        width: 80mm;
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        margin: 0;
                        padding: 5mm;
                    }
                    .header {
                            display: flex;
                            align-items: center;
                            margin-bottom: 5px;
                        }
                    .logo {
                            width: 60px;      /* ajustá según tu logo */
                            margin-right: 8px;
                        }
                    .header-text {
                            text-align: left;
                            flex: 1;
                        }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .line { border-top: 1px dashed #000; margin: 5px 0; }
                    .item { display: flex; justify-content: space-between; margin: 3px 0; }
                    .total { font-size: 14px; font-weight: bold; margin-top: 10px; }
                    h2 { margin: 5px 0; font-size: 16px; }
                    p { margin: 3px 0; }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="{{ asset('vendor/adminlte/dist/img/logo128.png') }}" class="logo">
                    <div class="header-text">
                        <h2>SAPP S.A.</h2>
                        <p>Calle Zorrilla s/n esq.Julieta</p>
                        <p>RUT: 211929570012</p>
                    </div>
                </div>
                
                <div class="line"></div>
                <h2>${datos.tipocobro}</h2>
                <div class="line"></div>
                <p>REF: ${datos.tipofactura} - ${datos.serie} ${datos.numero}</p>
                <p>Fecha: ${datos.fechadocu}</p>
                <p>Moneda: UYU </p>
                <div class="line"></div>

                <p class="bold">Cliente:</p>
                <p>${datos.cliente}</p>
                <p>UY: ${datos.tipodoc} ${datos.nroidentif}</p>
                <p>Dir: ${datos.direccion}</p>
                                
                <div class="line"></div>
                
                <div class="item">
                    <span>Cuota: ${datos.fecha}</span>
                    <span>$${datos.cuota}</span>
                </div>
                <div class="item">
                    <span>Tiquet:</span>
                    <span>$${datos.tiquet}</span>
                </div>
                <div class="item">
                    <span>Deudas:</span>
                    <span>$${datos.deudas}</span>
                </div>
                <div class="item">
                    <span>Promoción:</span>
                    <span>$${datos.promocion}</span>
                </div>
                
                <div class="line"></div>
                <div class="item">
                    <span>IVA 10%:</span>
                    <span>$${datos.iva}</span>
                </div>
                
                <div class="item total">
                    <span>TOTAL:</span>
                    <span>$${datos.total}</span>
                </div>
                
                <div class="line"></div>
                <p class="bold">ADENDA:</p>
                <p>${datos.adenda}</p>
                
                <p class="center">Gracias por preferirnos</p>
                <p class="center" style="font-size: 10px;">www.sapp.com.uy</p>
                
                <br><br>
            </body>
            </html>
        `;

            // Abrir ventana de impresión
            const ventana = window.open('', '_blank', 'width=300,height=600');
            ventana.document.write(contenido);
            ventana.document.close();

            // Esperar a que cargue y luego imprimir
            ventana.onload = function() {
                ventana.focus();
                ventana.print();
                // Cerrar ventana después de imprimir (opcional)
                setTimeout(() => {
                    ventana.close();
                }, 100);
            };
        }
    </script>
    <script>
        // Listener para confirmar impresión desde el modal
        window.addEventListener('confirmar-impresion', event => {
            Swal.fire({
                title: 'Confirma impresión?',
                html: "Se enviará el ticket a la impresora térmica",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, imprimir',
            }).then((result) => {
                if (result.value) {
                    @this.call('imprimirTicketTermica', event.detail.id)
                }
            });
        });

        // Listener para imprimir en impresora térmica
        window.addEventListener('imprimir-ticket-termica', event => {
            imprimirTicketTermico(event.detail.datos);
        });

        function imprimirTicketTermico(datos) {
            // Crear el contenido del ticket para impresora térmica 80mm
            const contenido = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    @page {
                        size: 80mm auto;
                        margin: 0;
                    }
                    body {
                        width: 80mm;
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        margin: 0;
                        padding: 5mm;
                    }
                    .header {
                            display: flex;
                            align-items: center;
                            margin-bottom: 5px;
                        }
                    .logo {
                            width: 60px;      /* ajustá según tu logo */
                            margin-right: 8px;
                        }
                    .header-text {
                            text-align: left;
                            flex: 1;
                        }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .line { border-top: 1px dashed #000; margin: 5px 0; }
                    .item { display: flex; justify-content: space-between; margin: 3px 0; }
                    .total { font-size: 14px; font-weight: bold; margin-top: 10px; }
                    h2 { margin: 5px 0; font-size: 16px; }
                    p { margin: 3px 0; }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="{{ asset('vendor/adminlte/dist/img/logo128.png') }}" class="logo">
                    <div class="header-text">
                        <h2>SAPP S.A.</h2>
                        <p>Calle Zorrilla s/n esq.Julieta</p>
                        <p>RUT: 211929570012</p>
                    </div>
                </div>
                
                <div class="line"></div>
                <h2>${datos.tipofactura} - ${datos.serie} ${datos.numero}</h2>
                <p>F.Pago: ${datos.forma_pago}</p>
                <p>Fecha: ${datos.fechadocu}</p>
                <p>Moneda: UYU </p>
                <div class="line"></div>
                <p class="center">${datos.consumidor}</p>
                <div class="line"></div>

                <p class="bold">Cliente:</p>
                <p>${datos.cliente}</p>
                <p>UY: ${datos.tipodoc} ${datos.nroidentif}</p>
                <p>Dir: ${datos.direccion}</p>
                                
                <div class="line"></div>
                
                <div class="item">
                    <span>Cuota: ${datos.fecha}</span>
                    <span>$${datos.cuota}</span>
                </div>
                <div class="item">
                    <span>Tiquet:</span>
                    <span>$${datos.tiquet}</span>
                </div>
                <div class="item">
                    <span>Deudas:</span>
                    <span>$${datos.deudas}</span>
                </div>
                <div class="item">
                    <span>Promoción:</span>
                    <span>$${datos.promocion}</span>
                </div>
                
                <div class="line"></div>
                <div class="item">
                    <span>IVA 10%:</span>
                    <span>$${datos.iva}</span>
                </div>
                
                <div class="item total">
                    <span>TOTAL:</span>
                    <span>$${datos.total}</span>
                </div>
                
                <div class="line"></div>
                <div class="header">
                    <img src="{{ asset('vendor/adminlte/dist/img/qrq.png') }}" class="logo">
                </div>
                <p>Código seguridad: ${datos.codigoseg}</p>
                <p>IVA AL DIA:</p>
                <p>NRO.de CAE: ${datos.nrocae}</p>
                <p>VENCIMIENTO: ${datos.vencimientocae}</p>
                <div class="line"></div>
                <p class="bold">ADENDA:</p>
                <p>${datos.adenda}</p>
                
                <p class="center">Gracias por preferirnos</p>
                <p class="center" style="font-size: 10px;">www.sapp.com.uy</p>
                
                <br><br>
            </body>
            </html>
        `;

            // Abrir ventana de impresión
            const ventana = window.open('', '_blank', 'width=300,height=600');
            ventana.document.write(contenido);
            ventana.document.close();

            // Esperar a que cargue y luego imprimir
            ventana.onload = function() {
                ventana.focus();
                ventana.print();
                // Cerrar ventana después de imprimir (opcional)
                setTimeout(() => {
                    ventana.close();
                }, 100);
            };
        }
    </script>

    {{-- Modal para seleccionar motivo de baja o devolución --}}
    @if ($modalMotivo)
        <div class="modal fade show" id="modalMotivo" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Seleccionar Motivo de {{ $tipoAccion === 'baja' ? 'Baja' : 'Devolución' }}
                        </h5>
                        <button type="button" class="close" wire:click="cerrarModalMotivo" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Por favor, seleccione el motivo de la {{ $tipoAccion === 'baja' ? 'baja' : 'devolución' }}
                        </div>

                        <div class="form-group">
                            <label for="motivoSeleccionado">Motivo:</label>
                            <select class="form-control" id="motivoSeleccionado" wire:model="motivoSeleccionado">
                                <option value="">Seleccione un motivo...</option>
                                @foreach ($motivos as $motivo)
                                    <option value="{{ $motivo->id }}">{{ $motivo->descrip }}</option>
                                @endforeach
                            </select>
                            @if (!$motivoSeleccionado && $mensajeError)
                                <small class="text-danger">Debe seleccionar un motivo</small>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" wire:click="procesarConMotivo"
                            wire:loading.attr="disabled">
                            <i class="fas fa-check"></i> Confirmar
                        </button>
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModalMotivo">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
