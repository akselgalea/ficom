@extends('layouts.app')
@section('content')

@php 
    #if(session('res') && session('res')['status'] == 400) $beca = session('res')['beca'];
@endphp 

<div class="container card form-container">
    <h2 class="form-title">Editar nivel</h2>

    <form method="post" action="{{ route('nivel.update', $nivel->id) }}" id="formNivel" class="mt-3 row">
        @method('patch')
        @csrf
        
        <div class="form-group mb-3 col-12">
            <label class="form-label" for="nombre">Nombre</label>
            <input
                type="text"
                class="form-control
                @error('nombre') is-invalid @enderror"
                id="nombre"
                name="nombre"
                value="{{old('nombre') ?? $nivel->nombre}}"
            />
            
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group mb-3 col-12">
            <label class="form-label" for="matricula">Matrícula</label>
            <input 
                type="number"
                class="form-control @error('matricula') is-invalid @enderror"
                id="matricula"
                name="matricula"
                value="{{old('matricula') ?? $nivel->matricula}}" 
            />
            
            @error('matricula')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group mb-3 col-12">
            <label class="form-label" for="arancel">Arancel</label>
            <input
                type="number"
                class="form-control @error('arancel') is-invalid @enderror"
                id="arancel"
                name="arancel"
                value="{{old('arancel') ?? $nivel->arancel}}" 
            />
            
            @error('arancel')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="buttons mb-3">
            <button type="submit" id="btn-enviar" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
@endsection