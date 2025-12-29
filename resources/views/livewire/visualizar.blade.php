{{-- resources/views/livewire/registros-table.blade.php --}}
<div>
    <div class="card" style="margin-top: 10px">
        <div class="card-body">
            <div class="row" style="padding-left: 10px">
                <div class="col-lg-9 col-md-8">
                    <h4 style="color: blue">Número de Cobrador: {{ auth()->user()->cod_sapp }} ->cobranza pendiente
                        ordenado por grupo familiar</h4>
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
            @if ($filtro_id == 2)
                <div class="col-lg-4 col-md-8">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nombre" name="nombre" wire:model="nombre"
                            max="20">
                    </div>
                </div>
            @endif
            <div class="col-lg-2 col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button wire:click="aplicarFiltro" class="btn btn-primary">
                        <i class="fas fa-filter"></i>Aplicar Filtro
                    </button>
                </div>
            </div>
            <div class="col-lg-2 col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button wire:click="toggleMapa" class="btn btn-primary">
                        <i class="fas fa-map-marked-alt"></i>
                        {{ $mostrarMapa ? 'Ocultar Mapa' : 'Ver Mapa' }}
                    </button>
                </div>
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
                    <table class="table table-sm table-responsive table-striped fs-6">
                        <thead class="bg-info">
                            <tr>
                                <th>D-1</th>
                                <th>D-2</th>
                                <th>Acción</th>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Total$</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Emitir</th>
                                <th>Baja</th>
                                <th>Devol.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registros as $registro)
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
                                            data-placement="right" title="Guardar"
                                            wire:click="guardarDesde({{ $registro->nro }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                    <td>{{ $registro->mes }}/{{ $registro->ano }} </td>
                                    <td>{{ $registro->apellidos }}</td>
                                    <td>${{ number_format($registro->total, 2, ',', '.') }}</td>
                                    <td>{{ $registro->dir_cli }}</td>
                                    <td>{{ $registro->tel_cli }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                            data-placement="right" title="Emitir ticket"
                                            wire:click="imprimirTicket({{ $registro->nro }})"><i
                                                class="fas fa-print"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                            data-placement="right" title="Baja"
                                            wire:click="guardarArqueoB({{ $registro->nro }})"><i
                                                class="fas fa-times"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                            data-placement="right" title="Devolución"
                                            wire:click="guardarArqueoD({{ $registro->nro }})"><i
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
                                    <p class="mb-2"><strong>${registro.apellidos}</strong></p>
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
    </script>
</div>
