@extends('layouts.app')
@section('content')

<div class="container card" style="max-width: 1080px">
    <div class="buttons botones-formulario mb-4">
        <a href="{{ route('estudiante.pagos', $estudiante->id) }}" class="btn btn-primary">Ver historial de pago</a>
        <a href="{{ route('estudiante.beca.edit', $estudiante->id) }}" class="btn btn-primary">Administrar beca</a>
    </div>
    
    
    <form method="POST" action="{{route('estudiante.update', $estudiante->id)}}" class="mt-3">
        @csrf
        <div class="header-estudiante">
          @include('estudiante.partials.matricula.preMatriculaHeader')
        </div>

        <fieldset class="row">
            <h2>Estudiante</h2>

            @include('estudiante.partials.perfil.estudiante')
        </fieldset>
        

        <fieldset class="row">
            <div class="apoderado-title mt-3 mb-1">
              <h2>Apoderado</h2>
            </div>

            @include('estudiante.partials.perfil.apoderadoTitular')
        </fieldset>
        
        <fieldset class="row">
            <div class="apoderado-title mt-3 mb-1">
                <h2>Apoderado suplente</h2>
            </div>
            
            @include('estudiante.partials.perfil.apoderadoSuplente')
        </fieldset>

        <fieldset class="row">
          <div class="apoderado-title mt-3 mb-1">
              <h2>Madre</h2>
          </div>
          
          @include('estudiante.partials.perfil.madre')
        </fieldset>

        <fieldset class="row">
          <div class="apoderado-title mt-3 mb-1">
              <h2>Padre</h2>
          </div>
          
          @include('estudiante.partials.perfil.padre')
        </fieldset>
        
        <div class="footer-estudiante">
          @include('estudiante.partials.matricula.preMatriculaFooter')
        </div>

        @if(auth()->user()->hasAnyRole('admin', 'matriculas'))
            <div class="buttons botones-formulario">
                <button type="button" id="btn-editar" class="btn btn-secondary" onclick="editar()">Editar</button>
                <button type="button" id="btn-cancelar" class="btn btn-danger" onclick="cancelEditar()" hidden>Cancelar</button>
                <button type="button" id="btnGenDoc" class="btn btn-secondary" hidden>Generar documento</button>
                <button type="submit" id="btn-enviar" class="btn btn-primary" hidden>Guardar</button>
            </div>
        @endif
        
      </form>
</div>
@endsection

@push('scripts')
    <script>
        const btnGen = document.getElementById('btnGenDoc');
        const btneditar = document.getElementById('btn-editar');
        const cancelar = document.getElementById('btn-cancelar');
        const enviar = document.getElementById('btn-enviar');
        const header = document.querySelector('.header-estudiante');
        const footer = document.querySelector('.footer-estudiante');

        header.style.display = "none";
        footer.style.display = "none";

        //Estudiante
        const nombres = document.getElementById('nombres');
        const apellidos = document.getElementById('apellidos');
        const run = document.getElementById('run');
        const email = document.getElementById('email');
        const edad = document.getElementById('edad');
        const fecha_nacimiento = document.getElementById('fecha_nacimiento');
        const genero = document.getElementById('genero');
        const nacionalidad = document.getElementById('nacionalidad');
        const direccion = document.getElementById('direccion');
        const enfermedades = document.getElementById('enfermedades');
        const persona_emergencia = document.getElementById('persona_emergencia');
        const telefono_emergencia = document.getElementById('telefono_emergencia');
        const nivel = document.getElementById('nivel');
        const prioridad = document.getElementById('prioridad');
        
        //Apoderado
        const a_nombre = document.getElementById('a_nombre');
        const a_rut = document.getElementById('a_rut');
        const a_telefono = document.getElementById('a_telefono');
        const a_email = document.getElementById('a_email');
        const a_direccion = document.getElementById('a_direccion');
        
        //Apoderado suplente
        const sub_nombre = document.getElementById('sub_nombre');
        const sub_rut = document.getElementById('sub_rut');
        const sub_telefono = document.getElementById('sub_telefono');
        const sub_email = document.getElementById('sub_email');
        const sub_direccion = document.getElementById('sub_direccion');

        //Madre
        const m_nombre = document.getElementById('m_nombre');
        const m_rut = document.getElementById('m_rut');
        const m_telefono = document.getElementById('m_telefono');
        const m_email = document.getElementById('m_email');
        const m_direccion = document.getElementById('m_direccion');

        //Padre
        const p_nombre = document.getElementById('p_nombre');
        const p_rut = document.getElementById('p_rut');
        const p_telefono = document.getElementById('p_telefono');
        const p_email = document.getElementById('p_email');
        const p_direccion = document.getElementById('p_direccion');
        
        btnGen.addEventListener('click', () => {
          window.print();
        });

        function editar() {
            btneditar.hidden = true;
            cancelar.hidden = false;
            enviar.hidden = false;
            btnGen.hidden = false;

            header.style.display = "block";
            footer.style.display = "block";
            enableInput();
        }

        function cancelEditar() {
            btneditar.hidden = false;
            cancelar.hidden = true;
            enviar.hidden = true;
            btnGen.hidden = true;

            header.style.display = "none";
            footer.style.display = "none";
            disableInput();
        }

        function enableInput() {
            apellidos.disabled = false;
            nombres.disabled = false;
            run.disabled = false;
            email.disabled = false;
            edad.disabled = false;
            fecha_nacimiento.disabled = false;
            genero.disabled = false;
            nacionalidad.disabled = false;
            direccion.disabled = false;
            enfermedades.disabled = false;
            persona_emergencia.disabled = false;
            telefono_emergencia.disabled = false;
            nivel.disabled = false;
            prioridad.disabled = false;
            
            a_nombre.disabled = false;
            a_rut.disabled = false;
            a_telefono.disabled = false;
            a_email.disabled = false;
            a_direccion.disabled = false;
            
            sub_nombre.disabled = false;
            sub_rut.disabled = false;
            sub_telefono.disabled = false;
            sub_email.disabled = false;
            sub_direccion.disabled = false;

            m_nombre.disabled = false;
            m_rut.disabled = false;
            m_telefono.disabled = false;
            m_email.disabled = false;
            m_direccion.disabled = false;

            p_nombre.disabled = false;
            p_rut.disabled = false;
            p_telefono.disabled = false;
            p_email.disabled = false;
            p_direccion.disabled = false;
        }

        function disableInput() {
            apellidos.disabled = true;
            nombres.disabled = true;
            run.disabled = true;
            email.disabled = true;
            edad.disabled = true;
            fecha_nacimiento.disabled = false;
            genero.disabled = true;
            nacionalidad.disabled = true;
            direccion.disabled = true;
            enfermedad.disabled = true;
            persona_emergencia.disabled = true;
            telefono_emergencia.disabled = true;
            nivel.disabled = true;
            prioridad.disabled = true;

            a_nombre.disabled = true;
            a_rut.disabled = true;
            a_telefono.disabled = true;
            a_email.disabled = true;
            a_direccion.disabled = true;

            sub_nombre.disabled = true;
            sub_rut.disabled = true;
            sub_telefono.disabled = true;
            sub_email.disabled = true;
            sub_direccion.disabled = true;

            m_nombre.disabled = true;
            m_rut.disabled = true;
            m_telefono.disabled = true;
            m_email.disabled = true;
            m_direccion.disabled = true;

            p_nombre.disabled = true;
            p_rut.disabled = true;
            p_telefono.disabled = true;
            p_email.disabled = true;
            p_direccion.disabled = true;
        }
    </script>

    @if($errors->any())
    <script type="text/javascript">
        editar();
    </script>
    @endif

    <script src="{{ asset('js/components/delete.js') }}"></script>
@endpush