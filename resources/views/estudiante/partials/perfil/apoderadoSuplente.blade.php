<div class="row mt-3">
  <div class="form-group mb-8 col-md-8 col-6">
      <label for="sub_nombre" class="form-label">Nombre completo</label>
      <input type="text" id="sub_nombre" name="sub_nombre" value="{{ old('sub_nombre') ?? $estudiante->apoderados['apoderado_suplente']['nombre'] ?? '' }}" class="form-control @error('sub_nombre') is-invalid @enderror" disabled>
      
      @error('sub_nombre')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="sub_rut" class="form-label">RUT</label>
    <input type="text" id="sub_rut" name="sub_rut" value="{{ old('sub_rut') ?? $estudiante->apoderados['apoderado_suplente']['rut'] ?? '' }}" placeholder="11111111-1" class="form-control @error('sub_rut') is-invalid @enderror" disabled>
        
    @error('sub_rut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-6">
      <label for="sub_telefono" class="form-label">Teléfono</label>
      <input type="text" id="sub_telefono" name="sub_telefono" value="{{ old('sub_telefono') ?? $estudiante->apoderados['apoderado_suplente']['telefono'] ?? '' }}" class="form-control @error('sub_telefono') is-invalid @enderror" disabled>
      
      @error('sub_telefono')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-6 col-12">
      <label for="sub_email" class="form-label">Correo Electrónico</label>
      <input type="email" id="sub_email" name="sub_email" value="{{ old('sub_email') ?? $estudiante->apoderados['apoderado_suplente']['email'] ?? '' }}" class="form-control @error('sub_email') is-invalid @enderror" disabled>
      
      @error('sub_email')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-12">
      <label for="sub_direccion" class="form-label">Dirección</label>
      <input type="text" id="sub_direccion" name="sub_direccion" value="{{ old('sub_direccion') ?? $estudiante->apoderados['apoderado_suplente']['direccion'] ?? '' }}" class="form-control @error('sub_direccion') is-invalid @enderror" disabled>
      
      @error('sub_direccion')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
</div>