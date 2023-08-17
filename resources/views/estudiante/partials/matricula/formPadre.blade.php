<div class="row mt-3">
  <h2 class="form-title">Datos del padre</h2>
  <div class="form-group mb-8 col-md-8 col-6">
      <label for="names" class="form-label">Nombre completo</label>
      <input type="text" id="names" name="p_nombre" value="{{ old('p_nombre') }}" class="form-control @error('p_nombre') is-invalid @enderror">
      
      @error('p_nombre')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="p_rut" class="form-label">RUT</label>
    <input type="text" id="p_rut" name="p_rut" value="{{ old('p_rut') }}" placeholder="11111111-1" class="form-control @error('p_rut') is-invalid @enderror">
        
    @error('p_rut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-6">
      <label for="p_telefono" class="form-label">Teléfono</label>
      <input type="text" id="p_telefono" name="p_telefono" value="{{ old('p_telefono') }}" class="form-control @error('p_telefono') is-invalid @enderror">
      
      @error('p_telefono')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-6 col-12">
      <label for="p_email" class="form-label">Correo Electrónico</label>
      <input type="email" id="p_email" name="p_email" value="{{ old('p_email') }}" class="form-control @error('p_email') is-invalid @enderror">
      
      @error('p_email')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-12">
      <label for="p_direccion" class="form-label">Dirección</label>
      <input type="text" id="p_direccion" name="p_direccion" value="{{ old('p_direccion') }}" class="form-control @error('p_direccion') is-invalid @enderror">
      
      @error('p_direccion')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
</div>