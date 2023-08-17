<div class="row mt-3">
  <div class="form-group mb-8 col-md-8 col-6">
      <label for="m_nombre" class="form-label">Nombre completo</label>
      <input type="text" id="m_nombre" name="m_nombre" value="{{ old('m_nombre') ?? $estudiante->apoderados['madre']['nombre'] ?? '' }}" class="form-control @error('m_nombre') is-invalid @enderror" disabled>
      
      @error('m_nombre')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="m_rut" class="form-label">RUT</label>
    <input type="text" id="m_rut" name="m_rut" value="{{ old('m_rut') ?? $estudiante->apoderados['madre']['rut'] ?? '' }}" placeholder="11111111-1" class="form-control @error('m_rut') is-invalid @enderror" disabled>
        
    @error('m_rut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-6">
      <label for="m_telefono" class="form-label">Teléfono</label>
      <input type="text" id="m_telefono" name="m_telefono" value="{{ old('m_telefono') ?? $estudiante->apoderados['madre']['telefono'] ?? '' }}" class="form-control @error('m_telefono') is-invalid @enderror" disabled>
      
      @error('m_telefono')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-6 col-12">
      <label for="m_email" class="form-label">Correo Electrónico</label>
      <input type="email" id="m_email" name="m_email" value="{{ old('m_email') ?? $estudiante->apoderados['madre']['email'] ?? '' }}" class="form-control @error('m_email') is-invalid @enderror" disabled>
      
      @error('m_email')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-12">
      <label for="m_direccion" class="form-label">Dirección</label>
      <input type="text" id="m_direccion" name="m_direccion" value="{{ old('m_direccion') ?? $estudiante->apoderados['madre']['direccion'] ?? '' }}" class="form-control @error('m_direccion') is-invalid @enderror" disabled>
      
      @error('m_direccion')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
</div>