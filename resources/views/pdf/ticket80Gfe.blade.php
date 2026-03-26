<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin-top: 5px;
            margin-bottom: 5px;
            margin-left: 5px;
            margin-right: 15px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .ticket {
            width: 240px;
            /* antes 280px */
            padding-right: 10px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla td {
            padding: 2px 0;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="ticket">

        <div class="center">

            <b>SAPP SOCIEDAD ANONIMA</b><br>
            <b>RUT:</b>211929570012<br>
            <b>Domicilio:</b>Zorrilla s/n -Salinas, Canelones<br>
            <hr>
            F.Pago:{{ $datos['forma_pago'] ?? 'CREDITO' }}<br>
            {{ $datos['tipofactura'] }} Serie: {{ $datos['serie'] }}<br>
            Número: {{ $datos['numero'] }}

        </div>

        <hr>

        Cliente:{{ $datos['nroidentif'] }}<br>
        {{ $datos['cliente'] }}<br>
        {{ $datos['direccion'] }}<br>
        Tel: {{ $datos['telefono'] }}

        <hr>
        @if ($datos['tipofactura'] == 'FACTURA')
            {{ $datos['consumidor'] }} <br>
            RUT: {{ $datos['rut'] }}<br>
        @else
            CONSUMIDOR FINAL<br>
            Otro: {{ $datos['nroidentif'] }} <br>
        @endif
        <hr>

        <table class="tabla">

            <tr>
                <td>Cuota</td>
                <td class="right">${{ $datos['cuota'] }}</td>
            </tr>

            <tr>
                <td>Ticket</td>
                <td class="right">${{ $datos['tiquet'] }}</td>
            </tr>

            <tr>
                <td>Deudas</td>
                <td class="right">${{ $datos['deudas'] }}</td>
            </tr>

            <tr>
                <td>Promoción</td>
                <td class="right">-${{ $datos['promocion'] }}</td>
            </tr>

            <tr>
                <td>IVA</td>
                <td class="right">${{ $datos['iva'] }}</td>
            </tr>

        </table>

        <hr>

        <table class="tabla total">

            <tr>
                <td>TOTAL</td>
                <td class="right">${{ $datos['total'] }}</td>
            </tr>

        </table>
        <hr>
        <table class="tabla total">
            <tr>
                <img style="width: 100px; height:80" src="vendor/adminlte/dist/img/qr.png" />
            </tr>
        </table>
        <hr>
        Cód.Seg: {{ $datos['codigoseg'] }}<br>
        Res.Nro: 2170/2016
        CAE: {{ $datos['nrocae'] }}<br>
        Vencimiento: {{ $datos['vencimientocae'] }}
        <hr>
        Adenda:<br>
        <div class="center">
            Empresa afiliada al Clearing de Informes.
            Este pago no cancela deudas anteriores.
            Sitio web: www.sapp.com.uy
        </div>
        <hr>

    </div>

</body>

</html>
