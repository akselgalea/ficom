@extends('layouts.app')
@section('content')

@php
    if(isset($res) && $res['status'] == 400) $estudiante = $res['estudiante'];
@endphp
        
<div class="container pre-matricula card form-container">
  <form method="post" action="{{ route('estudiante.store') }}" id="estudianteStore" class="mt-3 row">
    @csrf
      <div class="documento" class="p-2">
        @include('estudiante.partials.matricula.preMatriculaHeader')

        @include('estudiante.partials.matricula.formEstudiante')
        
        @include('estudiante.partials.matricula.formApoderado')

        @include('estudiante.partials.matricula.formApoderadoSuplente')

        @include('estudiante.partials.matricula.formMadre')

        @include('estudiante.partials.matricula.formPadre')

        @include('estudiante.partials.matricula.formSuplentes')

        @include('estudiante.partials.matricula.preMatriculaFooter')
      </div>

      <div class="my-3 botones-formulario">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" id="btnGenDoc" class="btn btn-secondary">Generar documento</button>
      </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
  const boton = document.getElementById('btnGenDoc');
  const formulario = document.getElementById('estudianteStore');

  formulario.addEventListener('submit', (event) => {
    event.preventDefault();
    const formData = new FormData(formulario);
    
    formulario.submit();
  });
  
  formulario.addEventListener('formdata', (event) => {
    const formData = event.formData;
    formData.set('suplentes', JSON.stringify(suplentes));
  })

  boton.addEventListener('click', () => {
    window.print();
  })
</script>
@endpush