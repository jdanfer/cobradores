{{-- resources/views/livewire/registros-table.blade.php --}}
<div>
    <!-- ... tu código anterior del botón y mapa ... -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Pendientes</h2>
        <button wire:click="toggleMapa" class="btn btn-primary">
            <i class="fas fa-map-marked-alt"></i>
            {{ $mostrarMapa ? 'Ocultar Mapa' : 'Ver Mapa' }}
        </button>
    </div>

    {{-- Mapa --}}
    @if ($mostrarMapa)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ubicaciones en el Mapa</h5>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px; width: 100%; display: block;"></div>
            </div>
        </div>
    @endif

    {{-- Tabla de Registros --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de cobranza pendiente</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabla-registros">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Importe</th>
                            <th>Dirección</th>
                            <th>Emitir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                            <tr wire:key="registro-{{ $registro->id }}" class="fila-registro clickable-row"
                                data-registro-id="{{ $registro->id }}" style="cursor: pointer;">
                                <td>{{ date('d-m-Y', strtotime($registro->fecha)) }}</td>
                                <td>{{ $registro->nombre }}</td>
                                <td>${{ number_format($registro->total, 2, ',', '.') }}</td>
                                <td>{{ $registro->dir_cli }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="#" data-toggle="tooltip"
                                        title="Emitir ticket" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
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
            </div>
        </div>
    </div>
</div>

@if ($mostrarMapa)
    <script>
        let map = null;
        let marcadores = {}; // Guardaremos marcador por ID del registro

        function initializeMap() {
            if (!document.getElementById('map')) return;

            // Destruir mapa anterior si existe
            if (map) {
                map.remove();
                map = null;
                marcadores = {};
            }

            setTimeout(() => {
                try {
                    const primeraLat = {{ $primeraUbicacion['lat'] ?? -34.6037 }};
                    const primeraLng = {{ $primeraUbicacion['lng'] ?? -58.3816 }};

                    map = L.map('map').setView([primeraLat, primeraLng], 13);
                    window.mapInstance = map;

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    const bounds = [];

                    @foreach ($registros as $registro)
                        @if ($registro->latitud && $registro->longitud)
                            const lat = {{ $registro->latitud }};
                            const lng = {{ $registro->longitud }};
                            const registroId = {{ $registro->id }};

                            const marker = L.marker([lat, lng])
                                .addTo(map)
                                .bindPopup(`
                                <div style="min-width:200px;">
                                    <h6><strong>{{ $registro->apellidos }}</strong></h6>
                                    <p><strong>Fecha:</strong> {{ date('d-m-Y', strtotime($registro->fecha)) }}</p>
                                    <p><strong>Importe:</strong> ${{ number_format($registro->total, 2, ',', '.') }}</p>
                                    <p><strong>Dirección:</strong> {{ $registro->dir_cli }}</p>
                                </div>
                            `);

                            // Guardamos referencia del marcador por ID
                            marcadores[registroId] = marker;
                            bounds.push([lat, lng]);

                            // === CLIC EN MARCADOR → SELECCIONA FILA EN TABLA ===
                            marker.on('click', function() {
                                // Quitar selección anterior
                                document.querySelectorAll('.fila-registro').forEach(row => {
                                    row.classList.remove('table-primary');
                                });

                                // Seleccionar la fila correspondiente
                                const fila = document.querySelector(`tr[data-registro-id="${registroId}"]`);
                                if (fila) {
                                    fila.classList.add('table-primary');
                                    fila.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }

                                // Disparar evento Livewire (opcional, si quieres reaccionar en el componente)
                                @this.call('seleccionarRegistro', registroId);
                            });
                        @endif
                    @endforeach

                    // Ajustar zoom
                    if (bounds.length > 0) {
                        map.fitBounds(bounds, {
                            padding: [50, 50]
                        });
                    }

                    setTimeout(() => map.invalidateSize(), 200);
                } catch (e) {
                    console.error('Error inicializando mapa:', e);
                }
            }, 200);
        }

        // === CLIC EN FILA → CENTRA MARCADOR ===
        document.addEventListener('click', function(e) {
            const fila = e.target.closest('.clickable-row');
            if (!fila) return;

            const registroId = fila.getAttribute('data-registro-id');

            // Resaltar fila
            document.querySelectorAll('.fila-registro').forEach(r => r.classList.remove('table-primary'));
            fila.classList.add('table-primary');

            // Centrar y abrir marcador si existe
            if (marcadores[registroId]) {
                const marker = marcadores[registroId];
                map.setView(marker.getLatLng(), 17);
                marker.openPopup();
            }

            // Opcional: avisar al componente Livewire
            @this.call('seleccionarRegistro', registroId);
        });

        // Inicialización
        document.addEventListener('livewire:load', () => {
            initializeMap();
        });

        window.addEventListener('livewire:update', () => {
            setTimeout(initializeMap, 100);
        });
    </script>
@endif

<style>
    .table-primary {
        background-color: #cfe2ff !important;
        font-weight: 600;
    }

    .clickable-row:hover {
        background-color: #f8f9fa;
    }
</style>
