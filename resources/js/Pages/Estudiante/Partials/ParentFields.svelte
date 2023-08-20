<script>
  export let parent = {
    nombre: '',
    rut: '',
    telefono: '',
    email: '',
    direccion: ''
  };

  export let type, errors;
</script>

<div class="row mt-3">
  <h2 class="form-title">Datos {type === 'madre' ? 'de la' : 'del'} {type}</h2>
  <div class="form-group mb-8 col-md-8 col-6">
    <label for="{type}_nombre" class="form-label">Nombre completo</label>
    <input type="text" id="{type}_nombre" class="form-control" bind:value={parent.nombre}>
  </div>

  <div class="form-group mb-3 col-md-4 col-6">
    <label for="{type}_rut" class="form-label">RUT</label>
    <input type="text" id="{type}_rut" placeholder="11111111-1" class="form-control" bind:value={parent.rut}>
  </div>

  <div class="form-group mb-3 col-6">
    <label for="{type}_telefono" class="form-label">Teléfono</label>
    <input type="text" id="{type}_telefono" class="form-control" bind:value={parent.telefono}>
  </div>

  <div class="form-group mb-3 col-md-6 col-12">
    <label for="{type}_email" class="form-label">Correo Electrónico</label>
    <input type="email" id="{type}_email" class="form-control" bind:value={parent.email}>
  </div>

  <div class="form-group mb-3 col-12">
    <label for="{type}_direccion" class="form-label">Dirección</label>
    <input type="text" id="{type}_direccion" class="form-control" bind:value={parent.direccion}>
  </div>
</div>