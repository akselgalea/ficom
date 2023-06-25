<div class="form-group mb-3 col-6">
    <label for="sub_nombres" class="form-label">Nombres</label>
    <input type="text" id="sub_nombres" name="sub_nombres" class="form-control @error('sub_nombres') is-invalid @enderror" 
        value="{{old('sub_nombres') ? old('sub_nombres') : ($hAS ? $estudiante->apoderado_suplente->nombres : '')}}" disabled
    >

    @error('sub_nombres')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6">
    <label for="sub_apellidos" class="form-label">Apellidos</label>
    <input type="text" id="sub_apellidos" name="sub_apellidos" class="form-control @error('sub_apellidos') is-invalid @enderror" 
        value="{{old('sub_apellidos') ? old('sub_apellidos') : ($hAS ? $estudiante->apoderado_suplente->apellidos : '')}}" disabled
    >

    @error('sub_apellidos')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-2">
    <label for="sub_telefono" class="form-label">Teléfono</label>
    <input type="text" id="sub_telefono" name="sub_telefono" class="form-control @error('sub_telefono') is-invalid @enderror" 
        value="{{old('sub_telefono') ? old('sub_telefono') : ($hAS ? $estudiante->apoderado_suplente->telefono : '')}}" disabled
    >

    @error('sub_telefono')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-5">
    <label for="sub_email" class="form-label">Correo Electrónico</label>
    <input type="email" id="sub_email" name="sub_email" class="form-control @error('sub_email') is-invalid @enderror" 
        value="{{old('sub_email') ? old('sub_email') : ($hAS ? $estudiante->apoderado_suplente->email : '')}}" disabled
    >

    @error('sub_email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-6 col-md-5">
    <label for="sub_direccion" class="form-label">Dirección</label>
    <input type="text" id="sub_direccion" name="sub_direccion" class="form-control @error('sub_direccion') is-invalid @enderror" 
        value="{{old('sub_direccion') ? old('sub_direccion') : ($hAS ? $estudiante->apoderado_suplente->direccion : '')}}" disabled
    >

    @error('sub_direccion')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>