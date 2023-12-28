@extends('adminlte::page')

@section('title', 'Sabores')

@section('content_header')
    <h1>Inventario</h1>
@stop

@section('content')
    <div id = "app">
      <inventario-component></inventario-component>
    </div>
@stop

@section('css')
    <!--<link rel="stylesheet" href="/css/admin_custom.css">-->
    @vite ('resources/css/app.css')
@stop

@section('js')
   <!-- <script> console.log('Hi!'); </script>-->
   @vite ('resources/js/app.js')
@stop