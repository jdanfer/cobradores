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
                    <strong>Cobrador:</strong> {{ $cobradornro }}
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
        $cantidadtotal = 0;
    @endphp

    @foreach ($registros as $color => $grupo)
        @php
            $subtotal = 0;
            $cantidadcolor = count($grupo);
            $cantidadtotal += $cantidadcolor;
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
                <td style="width: 50%; vertical-align: top; padding-right: 5px">
                    <table border="1" style="width: 100%; border-collapse: collapse">
                        <thead>
                            <tr style="font-size: 9pt; background-color: #f0f0f0">
                                <th style="width: 40%; text-align: left; padding: 3px">
                                    <strong>Matrícula</strong>
                                </th>
                                <th style="width: 30%; text-align: left; padding: 3px">
                                    <strong>Nro.Recibo</strong>
                                </th>
                                <th style="width: 30%; text-align: right; padding: 3px">
                                    <strong>Total $.</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grupoArray as $index => $registro)
                                @if ($index % 2 == 0)
                                    @php
                                        $subtotal += $registro['total'];
                                    @endphp
                                    <tr style="font-size: 8pt">
                                        <td style="padding: 2px">{{ $registro['matricula'] }}</td>
                                        <td style="padding: 2px">{{ $registro['nrorec'] }}</td>
                                        <td style="text-align: right; padding: 2px">
                                            {{ number_format($registro['total'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </td>

                <!-- Tabla Derecha -->
                <td style="width: 50%; vertical-align: top; padding-left: 5px">
                    <table border="1" style="width: 100%; border-collapse: collapse">
                        <thead>
                            <tr style="font-size: 9pt; background-color: #f0f0f0">
                                <th style="width: 40%; text-align: left; padding: 3px">
                                    <strong>Matrícula</strong>
                                </th>
                                <th style="width: 30%; text-align: left; padding: 3px">
                                    <strong>Nro.Recibo</strong>
                                </th>
                                <th style="width: 30%; text-align: right; padding: 3px">
                                    <strong>Total $.</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grupoArray as $index => $registro)
                                @if ($index % 2 != 0)
                                    @php
                                        $subtotal += $registro['total'];
                                    @endphp
                                    <tr style="font-size: 8pt">
                                        <td style="padding: 2px">{{ $registro['matricula'] }}</td>
                                        <td style="padding: 2px">{{ $registro['nrorec'] }}</td>
                                        <td style="text-align: right; padding: 2px">
                                            {{ number_format($registro['total'], 2, ',', '.') }}
                                        </td>
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
                <td style="width: 70%; text-align: left; padding: 5px">
                    <strong>Subtotal Color {{ $color }}: </strong> {{ $cantidadcolor }}
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
            <td style="width: 70%; text-align: left; padding: 7px">
                <strong>TOTAL GENERAL: </strong> {{ $cantidadtotal }}
            </td>
            <td style="width: 30%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalGeneral, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
</div>
