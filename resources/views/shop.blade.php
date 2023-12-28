@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 80px">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tienda</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h1 class="text-center" style="color: #FFB6C1;">Productos</h1>
                <style>
                    @keyframes shake {
                        0% { transform: translateX(0); }
                        25% { transform: translateX(-5px) rotate(0.5deg); }
                        50% { transform: translateX(5px) rotate(-0.5deg); }
                        75% { transform: translateX(-5px) rotate(0.5deg); }
                        100% { transform: translateX(0); }
                    }
                
                    .shake-effect {
                        animation: shake 2s ease-in-out infinite; /* Ajusta la duración según tus preferencias */
                    }
                </style>
                
                <h5 class="text-center shake-effect" style="color: #f03097; font-weight: bold; margin-top: 10px;">
                    Por la cantidad de 10 productos o más, tendrás que agendar el pedido con 5 días de anticipación.
                </h5>
                <hr> 
                <form action="{{ route('search.products') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Buscar productos">
                    <button type="submit" class="btn btn-outline-dark">Buscar</button>
                </div>
            </form>
                <div class="row">
                    @foreach($products as $pro)
                        <div class="col-md">
                            <div class="card" style="margin-bottom: 20px; height: auto;">
                                <img src="/images/productos/{{ $pro->imagen }}"
                                     class="card-img-top mx-auto"
                                     style="height: 150px; width: 150px;display: block;"
                                     alt="{{ $pro->image_path }}"
                                >
                                <div class="card-body" >
                                    <p href=""><h6 class="card-title">{{ $pro->nombre }}</h6>
                                        <ul>
                                            @foreach ($pro->sabores as $sabor)
                                                <li>{{ $sabor->nombre }}</li>
                                            @endforeach
                                            </ul></p>
                                    
                                    <p>${{ $pro->precio }}</p>
                                    
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" value="{{ $pro->id }}" id="id" name="id">
                                        <input type="hidden" value="{{ $pro->nombre }}" id="name" name="name">
                                        <input type="hidden" value="{{ $pro->precio }}" id="price" name="price">
                                        
                                        <input type="hidden" value="{{ $pro->image }}" id="img" name="img">
                                        <input type="hidden" value="{{ $pro->slug }}" id="slug" name="slug">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad:</label>
                                            <input type="number" value="0" id="quantity" name="quantity" min="1">
                                        </div>
                                        <div class="card-footer" style="background-color: white;">
                                              <div class="row">
                                                <button class="btn  btn-sm" class="tooltip-test" title="Agregar al Carrito" style="background: rgba(240, 193, 216, 0.929)">
                                                    <i class="fa fa-shopping-cart"></i> agregar al carrito
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                  @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection


