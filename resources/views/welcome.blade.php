
@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-vBZc9OdRhlUP6oMzQwmRgGZVPTIUnxzwvpr9a41Ji8d7O9gS+59p1X2T2bMIQbRBn4i8o3t+2jAjOhZcSbNTzLkg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- ESTE APARTADO ES DONDE EL CLIENTE VERA LOS PRODUCTOS -->
<!-- Dar color al menú -->
<nav class="navbar navbar-expand-md navbar-light" style="background-color: #D97AF7; margin-top: 40px; position: fixed; width: 100%; z-index: 999;">

    <div class="container-fluid">
        <!-- Botón para el menú responsivo -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Sección del menú en la barra de navegación -->
        <div class="collapse navbar-collapse position-relative" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item text-center">
                    <a class="navbar-brand" style="color:#FFFFFF" href="#carouselExampleSlidesOnly">Inicio</a>
                </li>
                <li class="nav-item text-center">
                    <a class="navbar-brand" style="color: #FFFFFF; font-size: 19px;" href="{{ url('/shop') }}">Productos</a>
                </li>
                <li class="nav-item text-center">
                    <a class="navbar-brand" style="color: #FFFFFF; font-size: 19px;" href="#acerca-de">Acerca de</a>
                </li>
                <li class="nav-item text-center">
                    <a class="navbar-brand" style="color: #FFFFFF; font-size: 19px;" href="#acerca-de">Contacto</a>
                </li>
               
            </ul>
        </div>
    </div>
</nav>
<br>
<br>
<br>
<br>
                  <!--carusel-->
                  <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img class="d-block w-100 img-fluid" src="{{ asset('imagenes/ice.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="First slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{ asset('imagenes/paleta.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="Second slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{ asset('imagenes/login.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="Third slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{ asset('imagenes/login.jpeg') }}"  style="height: 600px;" class="d-block w-100"  alt="Third slide">
                      </div>
                    </div>
                  </div>
                  <!--apartado de "acerca de nuestra pagina"-->
                  <br>
 <!-- Contenedor principal con clases de Bootstrap -->
<div class="container mt-5">
  <h3 id="acerca-de" class="card text-center mb-3" style=" background: #cb98d4">Acerca de</h3>
  <section class="banner" style="position: relative;">
      <img src="{{ asset('imagenes/local.jpeg') }}" class="d-block w-100" alt="" style="height: 600px;">
      <div  style="position: absolute; top: 7%; left: 50%; transform: translate(-40%, -40%); color: rgb(0, 0, 0); font-size: 40px; font-weight: bold; height: 0px;">HELADOS CHALATECOS GOURMET
      </div>
      <div class="mt-3">
          <h3 class="text-center font-weight-bold" style="color:#FF27D2;">Nuestro local</h3>
          <p class="text-center">
              Sumérgete en un ambiente lleno de dulzura y diversión, diseñado cuidadosamente para ofrecerte una experiencia única mientras disfrutas de nuestras deliciosas paletas artesanales.
              Cada detalle, desde la música alegre de fondo hasta la cálida iluminación que resalta los vibrantes colores de nuestras paletas, está pensado para cautivar tus sentidos y transportarte a un mundo de sabores exquisitos. En nuestro local, no solo encontrarás una amplia variedad de sabores y combinaciones, sino también un equipo amable que estará encantado de ayudarte a elegir la paleta perfecta para satisfacer tus antojos.
              Visítanos y descubre por qué nuestro local de paletas es el lugar ideal para deleitar tu paladar y crear momentos dulces y memorables. ¡Te esperamos con los brazos abiertos en nuestro encantador rincón rosa!
          </p>
      </div>
  </section>

  <section class="banner position-relative mt-5">
      <img src="{{ asset('imagenes/nosotros.jpeg') }}" class="img-fluid" alt="" style="height: 600px;">
      <div class="mt-3">
          <h3 class="text-center font-weight-bold" style="color:#FF27D2;">Nuestros productos</h3>
          <p class="text-center">
              En nuestro local de venta de paletas artesanales y Ice Roll hechos a mano, te ofrecemos una amplia variedad de sabores y combinaciones deliciosas. Nuestras paletas son auténticas obras maestras de sabor, desde los clásicos hasta opciones innovadoras que sorprenderán tu paladar. Además, podrás presenciar la preparación de los famosos Ice Rolls en el momento, donde personalizamos cada detalle según tus preferencias. También te brindamos la oportunidad de agregar toppings y coberturas para darle un toque extra de diversión y sabor a tus creaciones. En nuestro local, no solo encontrarás productos deliciosos, sino también una experiencia única y especial, donde nuestro amable personal te guiará durante todo el proceso. Te invitamos a visitarnos y descubrir la frescura, la creatividad y el sabor que encontrarás en cada bocado de nuestras paletas y Ice Rolls hechos con amor.
          </p>
      </div>
  </section>
</div>

                  <!--apartado del pie de la pagina -->
<footer class="text-black py-4 mt-5" style="background-color:#cb98d4;">
  <div class="container">
      <div class="row">
          <div class="col-md-4">
              <h4>Nuestra Heladería</h4>
              <p>¡Disfruta de nuestros deliciosos helados artesanales!</p>
              <p>La vida puede ser dulce en Cocoa Sweet. Prepáralos como quieras!</p>
          </div>
          <div class="col-md-4">
              <h4 style="font-family:  'Times New Roman', Times, serif">Redes Sociales</h4>
              <ul class="list-unstyled d-flex justify-content-around" >
                  <li><a href="https://www.facebook.com/profile.php?id=100063447984644&mibextid=ZbWKwL" target="_blank" style="color: black;" class="fab fa-facebook-f"></a></li>
                  <li><a href="https://www.instagram.com/cocoa_sweet_sv/" target="_blank" style="color: black;" class="fab fa-instagram"></a></li>
                  <li><a href="https://www.google.com/maps/place/Cocoa+Sweet/@14.0398707,-88.939954,17.24z/data=!4m14!1m7!3m6!1s0x8f6365c3a8060df1:0x15d78f9637f4c0dd!2sCocoa+Sweet!8m2!3d14.0398454!4d-88.9377959!16s%2Fg%2F11j51pfyg9!3m5!1s0x8f6365c3a8060df1:0x15d78f9637f4c0dd!8m2!3d14.0398454!4d-88.9377959!16s%2Fg%2F11j51pfyg9?entry=ttu" target="_blank" style="color: black" class="fas fa-map-marker-alt"></a></li>
              </ul>
              <p>VENGA A CONOCERNOS!!</p>
              <p>Calle principal del Barrio el Chile.<br>6 calle poniente y avenida Libertad, en Chalatenango.<br>A pocas cuadras del Dollarcity junto al MOCAR</p>
          </div>
          <div class="col-md-4">
              <h4>Horario de Atención</h4>
              <p>Martes a Domingos: 10:00 am - 6:00 pm (sin cerrar al mediodía)</p>
              <p>Lunes: CERRADO</p>
          </div>
      </div>
  </div>
</footer>

@endsection