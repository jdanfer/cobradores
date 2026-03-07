@extends('adminlte::page')
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
            @livewire('informes-cobrador')
        </div>
    </div>
@endsection
