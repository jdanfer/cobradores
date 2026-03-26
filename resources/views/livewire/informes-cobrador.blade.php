<div>
    {{-- Overlay: controlado solo por JS, no por wire:loading --}}
    <div id="overlay-procesando"
        style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(255, 255, 255, 0.7); z-index: 9999; 
            cursor: not-allowed; align-items: center; justify-content: center;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <h4 class="mt-2" style="color: blue">Procesando, por favor espere...</h4>
        </div>
    </div>

    <div>
        @if (auth()->user()->hcerol_id == 1)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 style="color: blue">Seleccione cobrador para informe</h4>
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
                        <div class="col-lg-1 col-md-8 pt-3">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="historico"
                                    wire:model="historico" id="historico">
                                <label class="custom-control-label" style="color: blue; font-size: 15px"
                                    for="historico">Historial</label>
                            </div>
                        </div>
                        @if ($historico)
                            <div class="col-lg-1 col-md-8">
                                <div style="display:flex; gap:0;">
                                    <input type="number" wire:model="mes" min="1" max="12"
                                        placeholder="Mes" style="width:60px;">
                                    <input type="number" wire:model="anio" min="2024" max="2025"
                                        placeholder="Año" style="width:80px;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 col-md-8">
                            <h4 style="color: blue">Informes para cobrador: {{ auth()->user()->name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="row" style="padding-left: 10px">
                    <h5 style="color: blue">Seleccione informe</h5>
                    <div class="col-lg-4 col-md-8">
                        <div class="input-group mb-3">
                            <select id="seleccion_cob" style="color: blue" class="form-control input-sm"
                                wire:model="seleccion_cob" wire:loading.attr="disabled" name="seleccion_cob">
                                <option value="">Seleccionar...</option>
                                <option value="Pendientes">Pendientes</option>
                                <option value="Devoluciones">Devoluciones</option>
                                <option value="Bajas">Bajas</option>
                                <option value="Cobrados">Cobrados</option>
                                <option value="Resumen">Resumen</option>
                                <option value="Reimpresion">Reimpresion</option>
                                <option value="Sinemision">Sin emisión actual</option>
                                <option value="Nuevos">Nuevos emisión actual</option>
                                @if (auth()->user()->hcerol_id == 1)
                                    <option value="porcentajes">Porcentajes de cobranza</option>
                                    <option value="historial">Historial informes</option>
                                    <option value="devolcobranza">Devolución cobranza</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-8 pt-3">
                        @if ($seleccion_cob == 'porcentajes' || $seleccion_cob == 'historial' || $seleccion_cob == 'devolcobranza')
                            <button type="button" id="btnProcesarPdf" class="btn btn-primary" data-toggle="tooltip"
                                data-placement="right" disabled title="Emitir informe seleccionado a pdf"
                                wire:click="informeCobrador">Procesar pdf
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        @else
                            <button type="button" id="btnProcesarPdf" class="btn btn-primary" data-toggle="tooltip"
                                data-placement="right" title="Emitir informe seleccionado a pdf"
                                wire:click="informeCobrador">Procesar pdf
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        @endif
                    </div>
                    <div class="col-lg-2 col-md-8 pt-3">
                        @if (
                            $seleccion_cob == 'Pendientes' ||
                                $seleccion_cob == 'Devoluciones' ||
                                $seleccion_cob == 'Bajas' ||
                                $seleccion_cob == 'Cobrados' ||
                                $seleccion_cob == 'Resumen' ||
                                $seleccion_cob == 'Reimpresion')
                            <button type="button" id="btnProcesarExcel" class="btn btn-success" data-toggle="tooltip"
                                data-placement="right" disabled title="Emitir informe seleccionado a excel"
                                wire:click="procesarExcel">Procesar excel
                                <i class="fas fa-file-excel"></i>
                            </button>
                        @else
                            <button type="button" id="btnProcesarExcel" class="btn btn-success"
                                data-toggle="tooltip" data-placement="right"
                                title="Emitir informe seleccionado a excel" wire:click="procesarExcel">Procesar excel
                                <i class="fas fa-file-excel"></i>
                            </button>
                        @endif
                    </div>
                    <br>
                    <div class="col-lg-2 col-md-8 pt-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="detalle" wire:model="detalle"
                                id="detalle">
                            <label class="custom-control-label" style="color: blue; font-size: 15px"
                                for="detalle">Detalle</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.admin.errors')
        <div>
            @if (session()->has('messageerror'))
                <div class="alert alert-danger alert-dismissible fade show alert-admin" role="alert">
                    {{ session('messageerror') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            const overlay = document.getElementById('overlay-procesando');

            Livewire.hook('message.sent', function(message, component) {
                const actionNames = (message.updateQueue || [])
                    .map(function(u) {
                        return u.payload && u.payload.method ? u.payload.method : '';
                    })
                    .filter(Boolean);

                if (actionNames.includes('informeCobrador') || actionNames.includes('procesarExcel')) {
                    overlay.style.display = 'flex';
                }
            });

            Livewire.hook('message.processed', function(message, component) {
                overlay.style.display = 'none';
            });

            Livewire.hook('message.failed', function(message, component) {
                overlay.style.display = 'none';
            });
        });
    </script>
</div>
