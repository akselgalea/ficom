<div class="row mt-3">
  <h2 class="form-title">Datos del apoderado</h2>
  <div class="form-group mb-8 col-md-8 col-6">
      <label for="names" class="form-label">Nombre completo</label>
      <input type="text" id="names" name="a_nombre" value="{{ old('a_nombre') }}" class="form-control @error('a_nombre') is-invalid @enderror">
      
      @error('a_nombre')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="a_rut" class="form-label">RUT</label>
    <input type="text" id="a_rut" name="a_rut" value="{{ old('a_rut') }}" placeholder="11111111-1" class="form-control @error('a_rut') is-invalid @enderror">
        
    @error('a_rut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-6">
      <label for="a_telefono" class="form-label">Teléfono</label>
      <input type="text" id="a_telefono" name="a_telefono" value="{{ old('a_telefono') }}" class="form-control @error('a_telefono') is-invalid @enderror">
      
      @error('a_telefono')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-6 col-12">
      <label for="a_email" class="form-label">Correo Electrónico</label>
      <input type="email" id="a_email" name="a_email" value="{{ old('a_email') }}" class="form-control @error('a_email') is-invalid @enderror">
      
      @error('a_email')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-12">
      <label for="a_direccion" class="form-label">Dirección</label>
      <input type="text" id="a_direccion" name="a_direccion" value="{{ old('a_direccion') }}" class="form-control @error('a_direccion') is-invalid @enderror">
      
      @error('a_direccion')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
</div>