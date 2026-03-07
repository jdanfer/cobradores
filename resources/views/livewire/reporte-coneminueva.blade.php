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
                    <strong>Informe de nuevos socios en emisión actual</strong>
                </th>
            </tr>
        </thead>
    </table>
    <br>

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
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $registro)
                <tr style="font-size: 8pt">
                    <td style="padding: 2px">{{ $registro->matricula }}</td>
                    <td style="padding: 2px">{{ $registro->categ }}</td>
                    <td style="padding: 2px">{{ $registro->nombre }}</td>
                    <td style="padding: 2px">{{ $registro->usuario_ced }}</td>
                    <td style="padding: 2px">{{ $registro->direccion }}</td>
                    <td style="padding: 2px">{{ $registro->zona }}</td>
                    <td style="padding: 2px">{{ $registro->grupof }}</td>
                    <td style="text-align: right; padding: 2px">
                        {{ number_format($registro->total, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
