@extends('adminlte::page')
@section('title', 'Usuarios')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@endsection
@section('content')
    @include('layouts.admin.message')
    @include('layouts.admin.errors')
    @include('layouts.admin.mensajealerta')
    <div class="card" style="margin-top: 10px">
        <div class="card-body">
            <div class="row" style="padding-left: 10px">
                <div class="col-lg-6 col-md-8">
                    <h1 style="color: blue">Usuarios registrados</h1>
                </div>
            </div>
            <div class="row" style="padding-left: 10px">
                <div class="col-lg-3 col-md-8">
                    <a href="{{ url('registrar') }}" class="btn btn-icon btn-2 btn-success">
                        <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                        Crear nuevo usuario</a>
                </div>
                <div class="col-lg-3 col-md-8">
                </div>

                <div class="col-lg-3 col-md-8">
                    <br>
                </div>
                <div class="col-lg-3 col-md-8">

                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="usuarios" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Documento</th>
                        <th>Editar</th>
                        <th>Baja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->documento }}</td>
                            <td style="width: 100px;">
                                <form action="{{ url('/usuarios/editar') }}" method="get">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-sm btn-primary">Editar</button>
                                </form>
                            </td>
                            <td style="width: 100px;">
                                @if ($user->baja === 1)
                                    <a class="btn btn-sm btn-warning">
                                        <span aria-hidden="true" class="glyphicon glyphicon-trash">
                                        </span>
                                        Desactivado</a>
                                @else
                                    <form action="{{ url('/usuarios/eliminar') }}" method="post">
                                        @csrf
                                        <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Seguro que deseas desactivar?')">Desactivar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script>
        new DataTable('#usuarios', {
            "pageLength": 50,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-AR.json',
            },
            responsive: true
        });
    </script>
@endsection
