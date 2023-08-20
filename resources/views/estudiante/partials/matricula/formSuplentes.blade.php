<section class="mt-3 row print-hidden">
  <div id="suplentes">
  </div>
  <div>
    <button type="button" class="btn btn-primary" id="btnAddSuplente">Añadir suplente para retiro</button>
  </div>
</section>

@push('scripts')
<script>
const btnAddSuplente = document.getElementById('btnAddSuplente');
let suplentes = [];
let suplenteCounter = 0;

btnAddSuplente.addEventListener('click', addSuplente);

function eliminarSuplente(event) {
  const itemToRemove = event.target.parentNode.parentNode;
  const num = itemToRemove.getAttribute('id');
  suplentes = suplentes.filter(item => item.id != num);
  itemToRemove.remove();
}

function addSuplente() {
  suplenteCounter++;
  let divSuplente = document.createElement('div');

  divSuplente.classList.add('row');
  divSuplente.classList.add('mt-3');
  divSuplente.id = `suplente${suplenteCounter}`;
  divSuplente.innerHTML = `
    <div class="d-flex justify-content-between">
      <h2 class="form-title">Datos de suplente para retiro</h2>
      <button type="button" class="btn btn-danger" onclick="eliminarSuplente(event)">Eliminar</button>
    </div>
    <div class="form-group mb-8 col-md-8 col-6">
        <label for="s${suplenteCounter}_nombre" class="form-label">Nombre completo</label>
        <input type="text" id="s${suplenteCounter}_nombre" name="s${suplenteCounter}_nombre" value="{{ old('s${suplenteCounter}_nombre') }}" class="form-control @error('s${suplenteCounter}_nombre') is-invalid @enderror">
        
        @error('s${suplenteCounter}_nombre')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-3 col-md-4 col-6">
      <label for="s${suplenteCounter}_rut" class="form-label">RUT</label>
      <input type="text" id="s${suplenteCounter}_rut" name="s${suplenteCounter}_rut" value="{{ old('s${suplenteCounter}_rut') }}" placeholder="11111111-1" class="form-control @error('s${suplenteCounter}_rut') is-invalid @enderror">
          
      @error('s${suplenteCounter}_rut')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
      @enderror
    </div>

    <div class="form-group mb-3 col-6">
        <label for="s${suplenteCounter}_telefono" class="form-label">Teléfono</label>
        <input type="text" id="s${suplenteCounter}_telefono" name="s${suplenteCounter}_telefono" value="{{ old('s${suplenteCounter}_telefono') }}" class="form-control @error('s${suplenteCounter}_telefono') is-invalid @enderror">
        
        @error('s${suplenteCounter}_telefono')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-md-6 col-12">
        <label for="s${suplenteCounter}_email" class="form-label">Correo Electrónico</label>
        <input type="email" id="s${suplenteCounter}_email" name="s${suplenteCounter}_email" value="{{ old('s${suplenteCounter}_email') }}" class="form-control @error('s${suplenteCounter}_email') is-invalid @enderror">
        
        @error('s${suplenteCounter}_email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-12">
        <label for="s${suplenteCounter}_direccion" class="form-label">Dirección</label>
        <input type="text" id="s${suplenteCounter}_direccion" name="s${suplenteCounter}_direccion" value="{{ old('s${suplenteCounter}_direccion') }}" class="form-control @error('s${suplenteCounter}_direccion') is-invalid @enderror">
        
        @error('s${suplenteCounter}_direccion')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>`;
  
  document.getElementById('suplentes').appendChild(divSuplente);
  suplentes.push({
    id: divSuplente.id,
    name: `s${suplenteCounter}_`
  });
} 
</script>
@endpush