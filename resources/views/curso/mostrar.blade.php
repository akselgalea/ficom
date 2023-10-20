@extends('layouts.app')
@section('content')
<div class="container card form-container">
    <div class="mb-3">
      <a href="{{ route('curso.pagos.generar', $curso->id)}}" class="btn btn-primary">Informe pagos</a>
    </div>

    <h2 class="form-title">{{ $curso->curso . '-' . $curso->paralelo }}</h2>
    
    <div class="form-group mb-3 col-12">
        <div class="form-label">Nivel</label>
        <div class="form-control">{{$curso->nivel->nombre}}</div>
    </div>

    <div class="form-group mb-3 col-12">
        <div class="form-label">Matrícula</label>
        <div class="form-control">{{toCLP($curso->nivel->periodo_actual->matricula)}}</div>
    </div>

    <div class="form-group mb-3 col-12">
        <div class="form-label">Arancel</label>
        <div class="form-control">{{toCLP($curso->nivel->periodo_actual->arancel)}}</div>
    </div>
</div>

<div class="container card mt-3" id="table">
    <h2>Estudiantes</h2>
    @if($curso->estudiantes->count() == 0)   
        <p>Este curso no tiene estudiantes en nuestros registros</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">RUN</th>
                    <th scope="col">Prioridad</th>
                    <th scope="col">Curso</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($curso->estudiantes as $estud)
                    <tr 
                        @switch ($estud->prioridad)
                            @case('alumno regular')
                                class="table-light"
                                @break
                        
                            @case('prioritario')
                                class="table-danger"
                                @break
                    
                            @case('nuevo prioritario')
                                class="table-primary"
                                @break
                        @endswitch
                    >
                        <td>{{ $estud->apellidos }}</td>
                        <td>{{ $estud->nombres }}</td>
                        <td>{{ $estud->rut . '-' . $estud->dv }}</td>
                        <td class="flc">{{ $estud->prioridad }}</td>
                        <td>
                            @if (isset($estud->curso))
                                {{ $estud->curso->curso . '-' . $estud->curso->paralelo }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('estudiante.show', $estud->id) }}" class="btn btn-primary" data-bs-toggle="tooltip" title="Perfil del estudiante">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                </svg>
                            </a>
                            <a href="{{ route('estudiante.pagos', $estud->id) }}" class="btn btn-secondary" data-bs-toggle="tooltip" title="Pagos del estudiante">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<style lang="scss">
    div.container#table {
        min-height: 500px
    }
    tr {vertical-align: middle}
    
    .buscador {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        margin-bottom: 1rem;
    }
</style>
@endsection