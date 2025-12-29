<div>

    <body class="hold-transition sidebar-mini">

        <!-- =========================================================== -->
        <h5 style="color:darkgreen" class="mb-2">Resumen de cobranza</h5>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-none">
                    <span class="info-box-icon bg-info"><i class="far fa-edit"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cobrados</span>
                        <span class="info-box-number">800 x $100000</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-success"><i class="far fa-edit"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pendientes</span>
                        <span class="info-box-number">800 x $100000</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-warning"><i class="far fa-edit"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Devoluciones</span>
                        <span class="info-box-number">800 x $100000</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box shadow-lg">
                    <span class="info-box-icon bg-danger"><i class="far fa-thumbs-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Bajas</span>
                        <span class="info-box-number">800 x $10000</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- =========================================================== -->
        <h5 style="color: cornflowerblue" class="mt-4 mb-2">Resumen de arqueo</h5>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-thumbs-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total $ entregado</span>
                        <span style="text-align: center" class="info-box-number">$.10.000,00</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 60%"></div>
                        </div>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-thumbs-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total $ restante</span>
                        <span style="text-align: center" class="info-box-number">$.10.000,00</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 40%"></div>
                        </div>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="far fa-edit"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Porcentaje cobranza</span>
                        <span style="text-align: center" class="info-box-number">75%</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 75%"></div>
                        </div>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- /.col -->
        </div>
        <br>
        <div class="row">
            <div class="col-lg-2 col-md-8">
                <label style="color: blue" for="filtro">
                    Seleccione cobrador:
                </label>
            </div>
            <div class="col-lg-2 col-md-8">
                <div class="input-group mb-3">
                    <select id="filtro_id" style="color: blue" class="form-control input-sm" wire:model="filtro_id"
                        name="filtro_id">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-8">
                <input type="date" class="form-control" id="fechad" name="fechad" wire:model="fechad"
                    value="{{ old('fechad') }}">
            </div>
            <div class="col-lg-2 col-md-8">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="arqueoant" wire:model="arqueoant"
                        id="arqueoant">
                    <label class="custom-control-label" style="color: blue; font-size: 15px" for="arqueoant">Arqueo
                        seleccionado</label>
                </div>
            </div>

            <div class="col-lg-2 col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button wire:click="aplicarFiltro" class="btn btn-primary">
                        <i class="fas fa-filter"></i>Aplicar Filtro
                    </button>
                </div>
            </div>

        </div>
    </body>
</div>
