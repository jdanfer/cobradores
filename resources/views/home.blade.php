@extends('adminlte::page')

@section('title', 'Cobranza domiciliaria SAPP')
@section('adminlte_css_pre')
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
@stop
@section('content_header')

@stop

@section('content')
    @if (auth()->user()->hcerol_id == 1)
        @livewire('visualizar-adm')
    @else
        @livewire('visualizar')
    @endif
@stop
