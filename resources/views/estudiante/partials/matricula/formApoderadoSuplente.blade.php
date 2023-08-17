<div class="row mt-3">
  <h2 class="form-title">Datos del apoderado suplente</h2>
  <div class="form-group mb-8 col-md-8 col-6">
      <label for="names" class="form-label">Nombre completo</label>
      <input type="text" id="names" name="sub_nombre" value="{{ old('sub_nombre') }}" class="form-control @error('sub_nombre') is-invalid @enderror">
      
      @error('sub_nombre')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="sub_rut" class="form-label">RUT</label>
    <input type="text" id="sub_rut" name="sub_rut" value="{{ old('sub_rut') }}" placeholder="11111111-1" class="form-control @error('sub_rut') is-invalid @enderror">
        
    @error('sub_rut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-6">
      <label for="sub_telefono" class="form-label">Teléfono</label>
      <input type="text" id="sub_telefono" name="sub_telefono" value="{{ old('sub_telefono') }}" class="form-control @error('sub_telefono') is-invalid @enderror">
      
      @error('sub_telefono')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-6 col-12">
      <label for="sub_email" class="form-label">Correo Electrónico</label>
      <input type="email" id="sub_email" name="sub_email" value="{{ old('sub_email') }}" class="form-control @error('sub_email') is-invalid @enderror">
      
      @error('sub_email')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-12">
      <label for="sub_direccion" class="form-label">Dirección</label>
      <input type="text" id="sub_direccion" name="sub_direccion" value="{{ old('sub_direccion') }}" class="form-control @error('sub_direccion') is-invalid @enderror">
      
      @error('sub_direccion')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
</div>