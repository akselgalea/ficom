<div class="form-group mb-3 col-md-4 col-6">
    <label for="apellidos" class="form-label">Apellidos</label>
    <input type="text" name="apellidos" id="apellidos"  class="form-control @error('apellidos') is-invalid @enderror" value="{{old('apellidos') ? old('apellidos') : $estudiante->apellidos}}" disabled>

    @error('apellidos')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-md-4 col-6">
    <label for="nombres" class="form-label">Nombres</label>
    <input type="text" name="nombres" id="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{old('nombres') ? old('nombres') : $estudiante->nombres}}" disabled>

    @error('nombres')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-md-4 col-6">
    <label for="run" class="form-label">RUN</label>
    <input type="text" name="run" id="run" class="form-control @error('run') is-invalid @enderror" value="{{old('run') ? old('run') : $estudiante->rut . '-' . $estudiante->dv}}" disabled>

    @error('run')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-md-4 col-6">
    <label for="email_institucional" class="form-label">Correo Institucional</label>
    <input type="email" name="email_institucional" id="email_institucional" class="form-control @error('email_institucional') is-invalid @enderror" value="{{old('email_institucional') ? old('email_institucional') : $estudiante->email_institucional}}" disabled>

    @error('email_institucional')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-md-4 col-6">
    <label for="nivel" class="form-label">Nivel</label>
    <select id="nivel" name="nivel" id="nivel" class="form-control form-select @error('nivel') is-invalid @enderror" disabled>
        @foreach ($cursos as $curso)
            <option value="{{$curso->id}}" @selected($estudiante->curso_id == $curso->id)>{{$curso->curso . '-' . $curso->paralelo}}</option>
        @endforeach
    </select>

    @error('nivel')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="form-group mb-3 col-md-4 col-6">
    <label for="prioridad" class="form-label">Prioridad</label>
    <select name="prioridad" id="prioridad" class="form-control form-select @error('prioridad') is-invalid @enderror" disabled>
        <option value="alumno regular" @selected($estudiante->prioridad == "alumno regular")>Alumno regular</option>
        <option value="nuevo prioritario" @selected($estudiante->prioridad == "nuevo prioritario")>Nuevo prioritario</option>
        <option value="prioritario" @selected($estudiante->prioridad == "prioritario")>Prioritario</option>
    </select>

    @error('prioridad')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>