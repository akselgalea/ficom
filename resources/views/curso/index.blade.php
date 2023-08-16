@extends('layouts.app')
@section('content')
    <div class="container">
        <h2 class="mb-3">Cursos</h2>
        
        @if(auth()->user()->hasAnyRole('contabilidad', 'admin'))
            <div class="buttons">
                @foreach ($niveles as $nivel)
                <a href="{{ route('nivel.cursos', ['id' => $nivel->id]) }}" class="btn btn-primary">{{ $nivel->nombre }}</a>
                @endforeach
            </div>
        @endif

        <div class="row my-3">
            @foreach($cursos as $curso)
            <div class="col-6 col-md-3 mb-3">
                <div class="card curso">
                    <div>
                        <div class="nombre">{{ $curso->curso . '-' . $curso->paralelo }}</div>
                        <div class="arancel"><b>Arancel:</b> {{ toCLP($curso->nivel->arancel) }}</div>
                        <div class="cantidad"><b>Cantidad estudiantes:</b> {{ $curso->estudiantes->count() }}</div>
                    </div>

                    <div class="buttons mt-2">
                        <a href="{{route('curso.show', $curso->id)}}" class="btn btn-primary">Ver</a>
                        <a href="{{ route('curso.pagos.generar', $curso->id)}}" class="btn btn-primary">Informe pagos</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection