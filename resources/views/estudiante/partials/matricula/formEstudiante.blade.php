<div class="row">
  <h2 class="form-title">Estudiante</h2>

  <div class="form-group mb-3 col-md-12 col-12">
    <label for="nivel" class="form-label">Nivel</label>
    <select id="nivel" name="nivel" class="form-control form-select @error('nivel') is-invalid @enderror">
        <option value="" selected disabled hidden>Selecciona una opción</option>
        @foreach ($cursos as $curso)
            <option value="{{$curso->id}}" @selected(old('nivel') == $curso->id)>{{$curso->curso . '-' . $curso->paralelo}}</option>
        @endforeach
    </select>

    @error('nivel')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-3 col-6">
      <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
      <input type="text" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}" class="form-control @error('apellido_paterno') is-invalid @enderror" autofocus>
          
      @error('apellido_paterno')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-3 col-6">
      <label for="apellido_materno" class="form-label">Apellido Materno</label>
      <input type="text" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno') }}" class="form-control @error('apellido_materno') is-invalid @enderror">
          
      @error('apellido_materno')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>
  <div class="form-group mb-3 col-md-6 col-6">
      <label for="nombres" class="form-label">Nombres</label>
      <input type="text" id="nombres" name="nombres" value="{{ old('nombres') }}" class="form-control @error('nombres') is-invalid @enderror">
          
      @error('nombres')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="run" class="form-label">RUN</label>
    <input type="text" id="run" name="run" value="{{ old('run') }}" placeholder="11111111-1" class="form-control @error('run') is-invalid @enderror">
        
    @error('run')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

<div class="form-group mb-3 col-md-4 col-6">
  <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
  <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"  class="form-control @error('fecha_nacimiento') is-invalid @enderror">
      
  @error('fecha_nacimiento')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
  @enderror
</div>

  <div class="form-group mb-3 col-md-4 col-6">
      <label for="prioridad" class="form-label">Prioridad</label>
      <select id="prioridad" name="prioridad" class="form-control form-select @error('prioridad') is-invalid @enderror">
          <option value="" selected disabled hidden>Selecciona una opción</option>
          <option @selected(old('prioridad') == 'alumno regular') value="alumno regular">Alumno regular</option>
          <option @selected(old('prioridad') == 'nuevo prioritario') value="nuevo prioritario">Nuevo prioritario</option>
          <option @selected(old('prioridad') == 'prioritario') value="prioritario">Prioritario</option>
      </select>

      @error('prioridad')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-8 col-6">
    <label for="email" class="form-label">Correo electrónico</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@ejemplo.cl"  class="form-control @error('email') is-invalid @enderror">
        
    @error('email')
      <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-2 col-6">
    <label for="edad" class="form-label">Edad</label>
    <input type="number" id="edad" name="edad" value="{{ old('edad') }}" placeholder="14" class="form-control @error('edad') is-invalid @enderror">
        
    @error('edad')
      <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
      </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-2 col-6">
      <label for="genero" class="form-label">Género</label>
      <select id="genero" name="genero" class="form-control form-select @error('genero') is-invalid @enderror">
          <option value="" selected disabled hidden>Selecciona una opción</option>
          <option @selected(old('genero') == 'M') value="M">Masculino</option>
          <option @selected(old('genero') == 'F') value="F">Femenino</option>
      </select>

      @error('genero')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="nacionalidad" class="form-label">Nacionalidad</label>
    <input type="text" id="nacionalidad" name="nacionalidad" value="{{ old('nacionalidad') }}" placeholder="Chilena" class="form-control @error('nacionalidad') is-invalid @enderror">
        
    @error('nacionalidad')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-8 col-12">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Tierra del Fuego #17, La Cruz, Quillota" class="form-control @error('direccion') is-invalid @enderror">
        
    @error('direccion')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-12 col-12">
    <label for="enfermedades" class="form-label">Enfermedades Contraindicaciones</label>
    <input type="text" id="enfermedades" name="enfermedades" value="{{ old('enfermedades') }}" placeholder="Asma" class="form-control @error('enfermedades') is-invalid @enderror">
        
    @error('enfermedades')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-6 col-6">
    <label for="persona_emergencia" class="form-label">En caso de emergencia remitir a</label>
    <input type="text" id="persona_emergencia" name="persona_emergencia" value="{{ old('persona_emergencia') }}" placeholder="Juan Pablo Perez Vargas" class="form-control @error('persona_emergencia') is-invalid @enderror">
        
    @error('persona_emergencia')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>

  <div class="form-group mb-3 col-md-6 col-6">
    <label for="telefono_emergencia" class="form-label">N° Telefónico</label>
    <input type="text" id="telefono_emergencia" name="telefono_emergencia" value="{{ old('telefono_emergencia') }}" placeholder="+56 9 71727374" class="form-control @error('run') is-invalid @enderror">
        
    @error('telefono_emergencia')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
  </div>
</div>