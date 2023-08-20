<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    @inertiaHead
  </head>
  <body>
    <nav id="navegador-top" class="navbar navbar-expand-md navbar-light shadow-sm navegador-principal">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img height="60px" src="{{ asset('img/logo.png') }}" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                            </li>
                        @endif
                        {{--@if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif--}}
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('estudiante.index') }}">Listado de estudiantes</a>
                        </li>
                        @if(auth()->user()->hasAnyRole('admin', 'matriculas'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('estudiante.create') }}">Estudiante nuevo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('subidaMasiva') }}">Subir registros</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="finanzasDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Finanzas
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a href="{{ route('beca.index') }}" class="dropdown-item">Becas</a>
                                <a href="{{ route('curso.index') }}" class="dropdown-item">Cursos</a>
                                <a href="{{ route('nivel.index') }}" class="dropdown-item">Niveles</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                  document.getElementById('logout-form').submit();">
                                    Cerrar sesión
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 main-container">
        {{-- Mensajes de alerta de las respuestas del controlador --}}
        @php if(session('res')) $res = session('res'); @endphp
        
        @if (isset($res))
            @if($res['status'] == 200)
                <div class="alert alert-success">
                    {{ $res['message'] }}
                </div>
            @elseif($res['status'] == 400)
                <div class="alert alert-danger">
                    {{ $res['message'] }}
                </div>
            @endif
            @php session()->forget('res'); @endphp
        @endif

        {{--
            Mensaje de alerta cuando el usuario no tiene permisos para acceder a una ruta 
                Vease el middleware CheckRole
        --}}
        @if (session('redirectMessage'))
            <div class="alert alert-info" role="alert">
                {{ session('redirectMessage') }}
            </div>

            @php session()->forget('redirectMessage'); @endphp
        @endif

        @inertia
    </main>
    <footer class="footer">
      © Copyright Colegio Simón Bolívar
    </footer>
  </body>
</html>