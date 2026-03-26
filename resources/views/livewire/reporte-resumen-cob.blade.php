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
                    <strong>RESUMEN DE COBRANZA</strong>
                </th>
            </tr>
        </thead>
    </table>
    <br>

    <!-- Encabezado del Grupo de Color -->
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Total cobrado SAPP:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalCobrado, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Total cobrado MUTUAL:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalMutual, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Total cobrado VIGILIA:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalVigilia, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Total general de cobranza:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalGeneral, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Total dinero entregado:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalEntregado, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Diferencia:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalDif, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Comisión SAPP:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalComision, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Comisión Mutual:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalcomisionmutual, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Comisión Vigilia:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalcomisionvigilia, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Otra comisión:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalOtras, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>
    <table border="1" style="width: 100%; margin-top: 10px">
        <tr style="background-color: #d0d0d0; font-size: 10pt">
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>Comisión TOTAL:</strong>
            </td>
            <td style="width: 50%; text-align: right; padding: 7px">
                <strong>$ {{ number_format($totalTotComision, 2, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <br>
    <div>
        <p>--------------------------------</p>
        <p>Firma del Cobrador</p>
        {{ $nombrecobrador }}</p>
    </div>
</div>
