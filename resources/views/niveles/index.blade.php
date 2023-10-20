@extends('layouts.app')
@section('content')
    <div class="container card">
        <h2 class="mb-3">Niveles</h2>

        <div class="row my-3">
            @foreach($niveles as $nivel)
            <div class="col-6 col-md-4 mb-3">
                <div class="card curso">
                    <div>
                        <div class="nombre">{{ $nivel->nombre }}</div>
                        <div class="arancel"><b>Matrícula:</b> {{ toCLP($nivel->periodo_actual->matricula) }}</div>
                        <div class="arancel"><b>Mensualidad:</b> {{ toCLP($nivel->periodo_actual->mensualidad) }}</div>
                        <div class="arancel"><b>Arancel:</b> {{ toCLP($nivel->periodo_actual->arancel) }}</div>
                    </div>

                    <div class="buttons mt-2">
                        <a href="{{ route('nivel.cursos', $nivel->id) }}" class="btn btn-primary">Ver</a>
                        @if(auth()->user()->hasAnyRole('admin', 'contabilidad'))
                        <a href="{{ route('nivel.edit', ['id' => $nivel->id]) }}" class="btn btn-primary">Editar</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection