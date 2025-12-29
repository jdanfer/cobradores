<div class="container mt-4">

    {{-- BOTÓN PARA VER EL MAPA --}}
    <a href="{{ route('mapa.registros') }}" class="btn btn-primary mb-3">
        Ver ubicaciones en el mapa
    </a>

    {{-- TABLA DE REGISTROS --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Importe</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $item)
                <tr>
                    <td>{{ $item->fecha }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->importe }}</td>
                    <td>{{ $item->direccion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
