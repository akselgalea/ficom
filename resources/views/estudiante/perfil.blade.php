@extends('layouts.app')
@section('content')

@php
    $hAT = $estudiante->hasApoderadoTitular();  //hasApoderadoTitular
    $hAS = $estudiante->hasApoderadoSuplente(); //hasApoderadoSuplente
@endphp

<div class="container">
    <div class="buttons mb-4">
        <a href="{{ route('estudiante.pagos', $estudiante->id) }}" class="btn btn-primary">Ver historial de pago</a>
        <a href="{{ route('estudiante.beca.edit', $estudiante->id) }}" class="btn btn-primary">Administrar beca</a>
    </div>

    <form method="POST" action="{{route('estudiante.update', $estudiante->id)}}" class="mt-3">
        @csrf
        <fieldset class="row">
            <h2>Estudiante</h2>

            @include('estudiante.partials.perfil.estudiante')
        </fieldset>
        

        <fieldset class="row">
            <div class="apoderado-title mt-3 mb-1">
                <h2>Apoderado</h2>
                @if($hAT)
                <div>
                    <button type="button" class="btn btn-sm btn-danger del" onclick="deleteSubmit('deleteApoderado')">Eliminar</button>
                </div>
                @endif
            </div>

            @include('estudiante.partials.perfil.apoderadoTitular')
        </fieldset>
        
        <fieldset class="row">
            <div class="apoderado-title mt-3 mb-1">
                <h2>Apoderado suplente</h2>
                @if($hAS)
                <div>
                    <button type="button" class="btn btn-sm btn-danger del" onclick="deleteSubmit('deleteApoderadoSuplente')">Eliminar</button>
                </div>
                @endif
            </div>
            
            @include('estudiante.partials.perfil.apoderadoSuplente')
        </fieldset>

        @if(auth()->user()->hasAnyRole('admin', 'matriculas'))
            <div class="buttons">
                <button type="button" id="btn-editar" class="btn btn-secondary" onclick="editar()">Editar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger" onclick="cancelEditar()" hidden>Cancelar</button>
                <button type="submit" id="btn-enviar" class="btn btn-primary" hidden>Guardar</button>
            </div>
        @endif
    </form>

    @if($hAT)
    <form method="post" action="{{route('estudiante.apoderado.remove', ['id' => $estudiante->id, 'apoderado' => $estudiante->apoderado_titular->id])}}" id="deleteApoderado">
        @method('delete')
        @csrf
    </form>
    @endif

    @if($hAS)
    <form method="post" action="{{route('estudiante.apoderado.remove', ['id' => $estudiante->id, 'apoderado' => $estudiante->apoderado_suplente->id])}}" id="deleteApoderadoSuplente">
        @method('delete')
        @csrf
    </form>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        const btneditar = document.getElementById('btn-editar');
        const cancelar = document.getElementById('btn-cancelar');
        const enviar = document.getElementById('btn-enviar');
        const deleteBtns = document.getElementsByClassName('del');
    
        for(let btn of deleteBtns) btn.hidden = true;

        //Estudiante
        const apellidos = document.getElementById('apellidos');
        const nombres = document.getElementById('nombres');
        const run = document.getElementById('run');
        const email_institucional = document.getElementById('email_institucional');
        const nivel = document.getElementById('nivel');
        const prioridad = document.getElementById('prioridad');
        
        //Apoderado
        const a_nombres = document.getElementById('a_nombres');
        const a_apellidos = document.getElementById('a_apellidos');
        const a_telefono = document.getElementById('a_telefono');
        const a_email = document.getElementById('a_email');
        const a_direccion = document.getElementById('a_direccion');
        
        //Apoderado suplente
        const sub_nombres = document.getElementById('sub_nombres');
        const sub_apellidos = document.getElementById('sub_apellidos');
        const sub_telefono = document.getElementById('sub_telefono');
        const sub_email = document.getElementById('sub_email');
        const sub_direccion = document.getElementById('sub_direccion');
        
        function editar() {
            for(let btn of deleteBtns) btn.hidden = false;
            btneditar.hidden = true;
            cancelar.hidden = false;
            enviar.hidden = false;
            enableInput();
        }

        function cancelEditar() {
            for(let btn of deleteBtns) btn.hidden = true;
            btneditar.hidden = false;
            cancelar.hidden = true;
            enviar.hidden = true;
            disableInput();
        }

        function enableInput() {
            apellidos.disabled = false;
            nombres.disabled = false;
            run.disabled = false;
            email_institucional.disabled = false;
            nivel.disabled = false;
            prioridad.disabled = false;
            
            a_nombres.disabled = false;
            a_apellidos.disabled = false;
            a_telefono.disabled = false;
            a_email.disabled = false;
            a_direccion.disabled = false;
            
            sub_nombres.disabled = false;
            sub_apellidos.disabled = false;
            sub_telefono.disabled = false;
            sub_email.disabled = false;
            sub_direccion.disabled = false;
        }

        function disableInput() {
            apellidos.disabled = true;
            nombres.disabled = true;
            run.disabled = true;
            email_institucional.disabled = true;
            nivel.disabled = true;
            prioridad.disabled = true;

            a_nombres.disabled = true;
            a_apellidos.disabled = true;
            a_telefono.disabled = true;
            a_email.disabled = true;
            a_direccion.disabled = true;

            sub_nombres.disabled = true;
            sub_apellidos.disabled = true;
            sub_telefono.disabled = true;
            sub_email.disabled = true;
            sub_direccion.disabled = true;
        }
    </script>

    @if($errors->any())
    <script type="text/javascript">
        editar();
    </script>
    @endif

    <script src="{{ asset('js/components/delete.js') }}"></script>
@endpush