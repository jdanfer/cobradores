@extends('adminlte::page')

@section('content')
    <div class="header bg-gradient-default pb-8 pt-5 pt-md-8">
        @include('admin.message')
        @include('admin.errors')
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Editar usuario</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ url('usuarios/editar') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input type="hidden" id="id" name="id" value="{{ $usuario->id }}">
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $usuario->name) }}" placeholder="Primer nombre" autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                        </div>
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" name="name2"
                                        class="form-control @error('name2') is-invalid @enderror"
                                        value="{{ old('name2', $usuario->name2) }}" placeholder="Primer apellido" autofocus>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                        </div>
                                    </div>
                                    @error('name2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="documento" name="documento"
                                                placeholder="Cédula" value="{{ old('documento', $usuario->documento) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-id-card"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="esadmin"
                                                id="esadmin" @if ($usuario->esadmin === 'on') checked @endif>
                                            <label class="custom-control-label" for="esadmin">Administrador</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $usuario->email) }}"
                                        placeholder="{{ __('adminlte::adminlte.email') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span
                                                class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                        </div>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <select id="hcerol_id" class="form-control input-sm" name="hcerol_id">
                                                <option value="">Seleccionar rol</option>
                                                @foreach ($roles as $rol)
                                                    @if (old('hcerol_id', $usuario->hcerol_id) == $rol->id)
                                                        <option value="{{ $rol->id }}" selected>{{ $rol->descrip }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $rol->id }}">{{ $rol->descrip }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span
                                                        class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Password field --}}
                                <div class="input-group mb-3">
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ __('adminlte::adminlte.password') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span
                                                class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- Confirm password field --}}
                                <div class="input-group mb-3">
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="{{ __('adminlte::adminlte.retype_password') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span
                                                class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <input style="width: 200px" type="text" class="form-control"
                                                id="pin" name="pin" placeholder="PIN"
                                                value="{{ old('pin') }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span
                                                        class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <input style="width: 200px" type="text" class="form-control"
                                                id="cod_sapp" name="cod_sapp" placeholder="Código en SAPP"
                                                value="{{ old('cod_sapp', $usuario->cod_sapp) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-id-card"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <br>
                                <br>
                                <div>
                                    <a class="btn btn-primary" href="{{ url('/usuarios') }}" role="button">Volver</a>
                                </div>
                            </div>
                        </form>
                        <br>
                        <br>
                        <div>
                            <form action="{{ url('/usuarios/eliminar/borrar') }}" method="post">
                                @csrf
                                <input type="hidden" id="id" name="id" value="{{ $usuario->id }}">
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Seguro que deseas borrar la cuenta?')">Borrar cuenta</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
