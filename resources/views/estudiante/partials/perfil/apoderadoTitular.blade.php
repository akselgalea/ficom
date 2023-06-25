<div class="form-group mb-3 col-6 col-md-6">
    <label for="a_nombres" class="form-label">Nombres</label>
    <input type="text" id="a_nombres" name="a_nombres" class="form-control @error('a_nombres') is-invalid @enderror" 
        value="{{old('a_nombres') ? old('a_nombres') : ($hAT ? $estudiante->apoderado_titular->nombres : '')}}" disabled
    >

    @error('a_nombres')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-6">
    <label for="a_apellidos" class="form-label">Apellidos</label>
    <input type="text" id="a_apellidos" name="a_apellidos" class="form-control @error('a_apellidos') is-invalid @enderror" 
        value="{{old('a_apellidos') ? old('a_apellidos') : ($hAT ? $estudiante->apoderado_titular->apellidos : '')}}" disabled
    >

    @error('a_apellidos')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-2">
    <label for="a_telefono" class="form-label">Teléfono</label>
    <input type="text" id="a_telefono" name="a_telefono" class="form-control @error('a_telefono') is-invalid @enderror" 
        value="{{old('a_telefono') ? old('a_telefono') : ($hAT ? $estudiante->apoderado_titular->telefono : '')}}" disabled
    >

    @error('a_telefono')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-5">
    <label for="a_email" class="form-label">Correo Electrónico</label>
    <input type="email" id="a_email" name="a_email" class="form-control @error('a_email') is-invalid @enderror" 
        value="{{old('a_email') ? old('a_email') : ($hAT ? $estudiante->apoderado_titular->email : '')}}" disabled
    >

    @error('a_email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-5">
    <label for="a_direccion" class="form-label">Dirección</label>
    <input type="text" id="a_direccion" name="a_direccion" class="form-control @error('a_direccion') is-invalid @enderror" 
        value="{{old('a_direccion') ? old('a_direccion') : ($hAT ? $estudiante->apoderado_titular->direccion : '')}}" disabled
    >

    @error('a_direccion')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>