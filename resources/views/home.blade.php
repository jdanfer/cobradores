@extends('adminlte::page')

@section('title', 'Cobranza domiciliaria SAPP')
@section('content_header')
    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->

    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

@stop

@section('content')
    @if (auth()->user()->hcerol_id == 1)
        @livewire('visualizar-adm')
    @else
        @livewire('visualizar')
    @endif
@stop
