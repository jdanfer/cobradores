{{-- resources/views/livewire/registros-table.blade.php --}}
<div>
    <div class="card" style="margin-top: 10px">
        <div class="card-body">
            <div class="row" style="padding-left: 10px">
                <div class="col-lg-9 col-md-8">
                    <h4 style="color: blue">Número de Cobrador: {{ auth()->user()->cod_sapp }} ->PENDIENTES DE COBRO</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-4">
        {{-- Mensajes de Éxito y Error --}}
        @if ($mensajeExito)
            <div id="mensajeExito" class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ $mensajeExito }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                    wire:click="$set('mensajeExito', null)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($mensajeError)
            <div id="mensajeError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ $mensajeError }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                    wire:click="$set('mensajeError', null)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-1 col-md-8">
                <label style="color: blue" for="filtro">
                    Filtro:
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
            @if (
                $filtro_id == 2 ||
                    $filtro_id == 3 ||
                    $filtro_id == 4 ||
                    $filtro_id == 5 ||
                    $filtro_id == 7 ||
                    $filtro_id == 8 ||
                    $filtro_id == 9)
                <div class="col-lg-4 col-md-8">
                    <div class="form-group">
                        @if ($filtro_id == 2)
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Escriba nombre..." wire:model="nombre" max="30">
                        @endif
                        @if ($filtro_id == 9)
                            <input type="text" class="form-control" id="zona" name="zona"
                                placeholder="Escriba zona..." wire:model="zona" max="30">
                        @endif
                        @if ($filtro_id == 3)
                            <input type="text" class="form-control" id="direccion" placeholder="Escriba dirección..."
                                name="direccion" wire:model="direccion" max="30">
                        @endif
                        @if ($filtro_id == 4)
                            <input type="text" class="form-control" id="telefono" placeholder="Escriba teléfono..."
                                name="telefono" wire:model="telefono" max="30">
                        @endif
                        @if ($filtro_id == 5)
                            <input type="text" class="form-control" id="grupofam"
                                placeholder="Escriba grupo familiar..." name="grupofam" wire:model="grupofam"
                                max="10">
                        @endif
                        @if ($filtro_id == 7)
                            <input type="text" class="form-control" id="cedula" placeholder="Escriba cédula..."
                                name="cedula" wire:model="cedula" max="10">
                        @endif
                        @if ($filtro_id == 8)
                            <input type="text" class="form-control" id="edocumento"
                                placeholder="Escriba número e-ticket..." name="edocumento" wire:model="edocumento"
                                max="10">
                        @endif

                    </div>
                </div>
            @endif
            <div class="col-lg-2 col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button wire:click="toggleMapa" class="btn btn-primary">
                        <i class="fas fa-map-marked-alt"></i>
                        {{ $mostrarMapa ? 'Ocultar Mapa' : 'Ver Mapa' }}
                    </button>
                </div>
            </div>
            <div class="col-lg-4 col-md-8">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="grupof" wire:model="grupof"
                        id="grupof">
                    <label class="custom-control-label" style="color: blue; font-size: 15px" for="grupof">Ordenar
                        por
                        Grupo familiar</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-8">
                <label style="color: blue; font-size: 20px;" for="totalcob">Total cobrados
                    $:{{ number_format($sumatotal, 2, ',', '.') }}</label>
            </div>
            <div class="col-lg-8 col-md-8">
                <label style="color: red; font-size: 20px;" for="totalpend">Dinero pendiente de entrega
                    $:{{ number_format($faltaentregar, 2, ',', '.') }}</label>
            </div>
        </div>
        {{-- Mapa --}}
        @if ($mostrarMapa)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Ubicaciones en el Mapa</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" wire:key="mapa-{{ count($registros) }}"
                        style="height: 500px; width: 100%; display: block;"></div>
                </div>
            </div>
        @endif
        {{-- Tabla de Registros --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-responsive table-striped fs-6" style="font-size: 0.9rem;">
                        <thead class="bg-info">
                            <tr>
                                <th>D-1</th>
                                <th>D-2</th>
                                <th>Acción</th>
                                <th>Nombre</th>
                                <th>Total$</th>
                                <th>Origen</th>
                                <th>Dirección</th>
                                <th>Zona</th>
                                @if ($filtro_id == 7)
                                    <th>Cédula</th>
                                @else
                                    @if ($filtro_id == 8)
                                        <th>Nro. Documento</th>
                                    @else
                                        <th>Teléfono</th>
                                    @endif
                                @endif
                                <th>GpoF</th>
                                <th>Emitir</th>
                                <th>Baja</th>
                                <th>Devol.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registros as $index => $registro)
                                <tr>
                                    <td>
                                        <input type="number" min="0" max="30"
                                            wire:model.defer="desde.{{ $registro->nro }}"
                                            class="form-control form-control-sm flex-shrink-0"
                                            value="{{ $registro->dia1 }}" placeholder="0" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="number" wire:model.defer="hasta.{{ $registro->nro }}"
                                            class="form-control form-control-sm flex-shrink-0"
                                            value="{{ $registro->dia2 }}" placeholder="0" style="width: 50px;">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="tooltip"
                                            data-placement="right" title="Guardar" wire:loading.attr="disabled"
                                            wire:click="guardarDesdeIndex({{ $index }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                    <td>{{ $registro->nombre }}</td>
                                    <td>
                                        <a href="#"
                                            wire:click.prevent="mostrarDetalleIndex({{ $index }})"
                                            class="text-primary font-weight-bold"
                                            style="cursor: pointer; text-decoration: none;" data-toggle="tooltip"
                                            data-placement="top" title="Ver detalle">
                                            ${{ number_format($registro->total, 2, ',', '.') }}
                                        </a>
                                    </td>
                                    <td>{{ $registro->origen }}</td>
                                    <td>{{ $registro->dir_cli }}</td>
                                    <td>{{ $registro->loc_cli }}</td>
                                    @if ($filtro_id == 7)
                                        <td>{{ $registro->usuario_ced }}</td>
                                    @else
                                        @if ($filtro_id == 8)
                                            <td>{{ $registro->nrorec }}</td>
                                        @else
                                            <td>{{ $registro->tel_cli }}</td>
                                        @endif
                                    @endif
                                    <td>{{ $registro->grupof }}</td>
                                    <td>
                                        <button type="button" id="btnCancelaTck" class="btn btn-primary btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Emitir ticket"
                                            wire:click="confirmarImpresionIndex({{ $index }})"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" id="btnBaja" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Baja"
                                            wire:loading.attr="disabled"
                                            wire:click="solicitarMotivoBajaIndex({{ $index }})"><i
                                                class="fas fa-times"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" id="btnDevol" class="btn btn-danger btn-sm"
                                            data-toggle="tooltip" data-placement="right" title="Devolución"
                                            wire:loading.attr="disabled"
                                            wire:click="solicitarMotivoDevolucionIndex({{ $index }})"><i
                                                class="fas fa-reply"></i>
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
    </div>

    @if ($mostrarMapa)
        <script>
            var globalMarkersData = @json($this->markers_data);
            var globalPrimeraUbicacion = {
                lat: {{ $primeraUbicacion['lat'] }},
                lng: {{ $primeraUbicacion['lng'] }}
            };

            document.addEventListener('livewire:load', function() {
                if (document.getElementById('map')) {
                    initializeMap(globalMarkersData, globalPrimeraUbicacion);
                }
            });

            // Recibe actualización de Livewire con los datos correctos
            window.addEventListener('mapa-actualizado', event => {
                initializeMap(event.detail.markersData, event.detail.primeraUbicacion);
            });

            function initializeMap(markersData, primeraUbicacion) {

                // Si el div no existe, no hacemos nada
                if (!document.getElementById('map')) {
                    return;
                }

                // Destruir mapa previa instancia
                if (window.mapInstance) {
                    window.mapInstance.remove();
                    window.mapInstance = null;
                }

                // Delay para que Livewire termine de renderizar
                setTimeout(function() {
                    try {
                        // Crear mapa
                        var map = L.map('map', {
                            closePopupOnClick: false
                        }).setView(
                            [primeraUbicacion.lat, primeraUbicacion.lng],
                            13
                        );

                        window.mapInstance = map;

                        // Capa de OpenStreetMap
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        // Marcadores
                        var markers = [];

                        markersData.forEach(function(registro) {
                            if (registro.lat && registro.lng) {

                                var marker = L.marker([registro.lat, registro.lng])
                                    .addTo(map)
                                    .bindPopup(`
                                <div style="min-width: 200px;">
                                    <p class="mb-2"><strong>${registro.nombre}</strong></p>
                                    <p class="mb-1"><strong>Teléfonos:</strong> ${registro.tel_cli}</p>
                                    <p class="mb-1"><strong>Fec.Cobro:</strong> ${registro.dia1} al ${registro.dia2}</p>
                                    <p class="mb-0"><strong>Dirección:</strong> ${registro.direccion}</p>
                                </div>
                            `);

                                markers.push(marker);
                            }
                        });

                        // Ajustar vista del mapa
                        if (markers.length > 1) {
                            var group = new L.featureGroup(markers);
                            map.fitBounds(group.getBounds().pad(0.1));
                        } else if (markers.length === 1) {
                            map.setView(
                                [primeraUbicacion.lat, primeraUbicacion.lng],
                                15
                            );
                        }

                        // ⚡ Abrir todos los popups con delay correcto
                        setTimeout(() => {
                            markers.forEach(marker => marker.openPopup());
                            map.invalidateSize();
                        }, 300);

                    } catch (error) {
                        console.error("Error al inicializar el mapa:", error);
                    }
                }, 150);
            }
        </script>
    @endif
    <script>
        window.addEventListener('imprime-cobro', event => {
            Swal.fire({
                title: `¿Confirma cobro a: ${event.detail.nombre}?`,
                html: "Verifique si la impresora está lista.",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, cobrar.',
            }).then((result) => {
                if (result.value) {
                    @this.call('imprimirTicket', event.detail.id)
                }
            });
        });

        function confirmarBaja(index) {
            Swal.fire({
                title: '¿Confirma la baja?',
                html: "Esta acción marcará la emisión como baja",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, dar de baja',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    @this.call('guardarArqueoBIndex', index);
                }
            });
        }

        // Función para confirmar devolución
        function confirmarDevolucion(index) {
            Swal.fire({
                title: '¿Confirma la devolución?',
                html: "Esta acción marcará la emisión como devolución",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, devolver',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    @this.call('guardarArqueoDIndex', index);
                }
            });
        }

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
                <p>Cobrador por:${datos.usuariocob}</p>
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
    {{-- Modal Detalle de Factura --}}
    @if ($modalDetalle)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1"
            role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-invoice"></i>
                            Detalle de Factura
                        </h5>
                        <button type="button" class="close text-white" wire:click="cerrarModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($facturaSeleccionada)
                            {{-- Información General --}}
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <strong>Información General</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Número:</strong> {{ $facturaSeleccionada->nrorec }}</p>
                                            <p><strong>Cliente:</strong> {{ $facturaSeleccionada->nombre }}</p>
                                            <p><strong>Teléfono:</strong> {{ $facturaSeleccionada->tel_cli }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Fecha:</strong>
                                                {{ $facturaSeleccionada->mes }}/{{ $facturaSeleccionada->ano }}</p>
                                            <p><strong>Días de cobro:</strong> {{ $facturaSeleccionada->dia1 }} al
                                                {{ $facturaSeleccionada->dia2 }}</p>
                                            <p><strong>Dirección:</strong> {{ $facturaSeleccionada->dir_cli }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detalle de Items --}}
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <strong>Detalle de Items</strong>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    @if ($facturaSeleccionada->mes == 0)
                                                        <th>Deuda $.</th>
                                                    @else
                                                        <th>Cuota</th>
                                                    @endif
                                                    <th>Tiquet</th>
                                                    <th>Deudas</th>
                                                    <th>Promoción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>${{ number_format($facturaSeleccionada->importe, 2, ',', '.') }}
                                                    </td>
                                                    <td>${{ number_format($facturaSeleccionada->tiquet, 2, ',', '.') }}
                                                    </td>
                                                    <td>${{ number_format($facturaSeleccionada->deudas, 2, ',', '.') }}
                                                    </td>
                                                    <td>${{ number_format($facturaSeleccionada->descimp, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Total --}}
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-right">
                                            <h4>
                                                <strong>Total: </strong>
                                                <span class="text-success">
                                                    ${{ number_format($facturaSeleccionada->total, 2, ',', '.') }}
                                                </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success"
                            wire:click="confirmarImpresion({{ $facturaSeleccionada->nro }})">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
                        <div>
                            <label style="color: red;font-size: 15px" for="nombre">Socio:
                                {{ $nombrecli }}</label>
                        </div>
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

    <script>
        window.addEventListener('abrir-pdf', event => {

            let url = event.detail.url;

            window.open(url, '_blank');

        });
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
    @if ($haynuevas > 0)
        @section('js')
            <script>
                Swal.fire({
                    width: 700,
                    background: 'red',
                    html: `<p style='color:white;font-size: 30px'><strong>NUEVAS ENTREGAS!!</strong></p>
                <p style='color:white;font-size: 15px'>Atención, tiene nuevas entregas asignadas.</p>
        <p style='color:white;font-size: 15px'>Puede visualizar las mismas, seleccionando el filtro Nuevas Entregas.</p>
        <p style='color:white;font-size: 15px'>Mientras no seleccione dicho filtro, se continuará mostrando</p>
        <p style='color:white;font-size: 15px'>este mensaje cada vez que inicie su pantalla principal.</p>
        `,
                });
            </script>
        @endsection
    @endif

</div>
