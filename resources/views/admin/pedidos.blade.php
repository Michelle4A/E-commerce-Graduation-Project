@extends('adminlte::page')

@section('title', 'Rentas')

@section('content_header')
    <h1>GESTION DE PEDIDOS</h1>
@stop

@section('content')
    <div id = "app">
      <pedidos-list></pedidos-list>
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