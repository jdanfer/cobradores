<div>
    <table>
        <thead>
            <tr>
                <th style="width: 160px">
                    <a>
                        <img style="width: 100px; height:40" id="header-logo" src="vendor/adminlte/dist/img/logo2p.png" />
                    </a>
                </th>
                <th style="width: 150px; font-size: 10pt; text-align: center">
                    <strong>Cobrador:</strong> {{ auth()->user()->cod_sapp }}
                </th>
                <th style="width: 190px; font-size: 10pt; text-align: center">
                    <strong>Fecha:</strong>
                    {{ date('d-m-Y') }}
                    {{ date('H:i') }}
                </th>
            </tr>
        </thead>
    </table>
    <hr>
    <table border="1">
        <thead>
            <tr style="font-size: 10pt">
                <th style="width: 100%; text-align: left">
                    <strong>Informe de: {{ $seleccion_cob }}</strong>
                </th>
            </tr>
        </thead>
    </table>
    <br>

    @php
        $totalGeneral = 0;
    @endphp

    @foreach ($registros as $color => $grupo)
        @php
            $subtotal = 0;
            $grupoArray = $grupo->toArray();
        @endphp

        <!-- Encabezado del Grupo de Color -->
        <table border="1" style="width: 100%; margin-bottom: 5px">
            <tr style="background-color: #e0e0e0; font-size: 10pt">
                <th style="text-align: left; padding: 5px">
                    <strong>Color: {{ $color }}</strong>
                </th>
            </tr>
        </table>

        <table border="1" style="width: 100%">
            <tr>
                <!-- Tabla Izquierda -->
                <td style="width: 100%; vertical-align: top; padding-right: 5px">
                    <table border="1" style="width: 100%; border-collapse: collapse">
                        <thead>
                            <tr style="font-size: 9pt; background-color: #f0f0f0">
                                <th style="width: 5%; text-align: left; padding: 3px">
                                    <strong>Matrícula</strong>
                                </th>
                                <th style="width: 5%; text-align: left; padding: 3px">
                                    <strong>Convenio</strong>
                                </th>
                                <th style="width: 25%; text-align: left; padding: 3px">
                                    <strong>Nombre</strong>
                                </th>
                                <th style="width: 5%; text-align: left; padding: 3px">
                                    <strong>Cédula</strong>
                                </th>
                                <th style="width: 20%; text-align: left; padding: 3px">
                                    <strong>Dirección</strong>
                                </th>
                                <th style="width: 5%; text-align: left; padding: 3px">
                                    <strong>Radio</strong>
                                </th>
                                <th style="width: 5%; text-align: left; padding: 3px">
                                    <strong>Gpo.F</strong>
                                </th>
                                <th style="width: 10%; text-align: right; padding: 3px">
                                    <strong>Total $.</strong>
                                </th>
                                @if ($seleccion_cob == 'Devolución')
                                    <th style="width: 20%; text-align: left; padding: 3px">
                                        <strong>Motivo</strong>
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grupoArray as $index => $registro)
                                @if ($index % 2 == 0)
                                    @php
                                        $subtotal += $registro['importe'];
                                    @endphp
                                    <tr style="font-size: 8pt">
                                        <td style="padding: 2px">{{ $registro['matricula'] }}</td>
                                        <td style="padding: 2px">{{ $registro['cat'] }}</td>
                                        <td style="padding: 2px">{{ $registro['nombre'] }}</td>
                                        <td style="padding: 2px">{{ $registro['usuario_ced'] }}</td>
                                        <td style="padding: 2px">{{ $registro['dir_cli'] }}</td>
                                        <td style="padding: 2px">{{ $registro['codzon'] }}</td>
                                        <td style="padding: 2px">{{ $registro['grupof'] }}</td>
                                        <td style="text-align: right; padding: 2px">
                                            {{ number_format($registro['total'], 2, ',', '.') }}
                                        </td>
                                        @if ($seleccion_cob == 'Devolución')
                                            <td style="padding: 2px">{{ $registro['motivo'] }}</td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Subtotal del Color -->
        <table border="1" style="width: 100%; margin-bottom: 10px">
            <tr style="background-color: #f9f9f9; font-size: 9pt">
                <td style="width: 70%; text-align: right; padding: 5px">
                    <strong>Subtotal Color {{ $color }}:</strong>
                </td>
                <td style="width: 30%; text-align: right; padding: 5px">
                    <strong>$ {{ number_format($subtotal, 2, ',', '.') }}</strong>
                </td>
            </tr>
        </table>

        @php
            $totalGeneral += $subtotal;
        @endphp
    @endforeach

    <!-- Total General -->
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 70%; text-align: right; padding: 7px">
                <strong>TOTAL GENERAL:</strong>
            </td>
            <td style="width: 30%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalGeneral, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
</div>
