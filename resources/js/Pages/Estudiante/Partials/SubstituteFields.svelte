<script>
  export let suplentes = [], errors;

  let counter = 0;

  function addSuplente() {
    counter++;

    suplentes = [...suplentes, {
      id: `s${counter}`,
      nombre: '',
      rut: '',
      telefono: '',
      email: '',
      direccion: ''
    }];
  }

  function removeSuplente(id) {
    if(confirm('¿Estás seguro de que deseas eliminar a este suplente?')) {
      suplentes = suplentes.filter(s => s.id !== id)
    }
  }
</script>

<section class="mt-3 row print-hidden">
  {#each suplentes as sup, index (index)}
  <div class="row mt-3">
    <div class="d-flex justify-content-between">
      <h2 class="form-title">Datos del suplente para retiro {index + 1}</h2>
      <button type="button" class="btn btn-danger" on:click={() => removeSuplente(sup.id)}>Eliminar</button>
    </div>

    <div class="form-group mb-8 col-md-8 col-6">
      <label for="{sup.id}_nombre" class="form-label">Nombre completo</label>
      <input type="text" id="{sup.id}_nombre" class="form-control" bind:value={sup.nombre}>
    </div>
  
    <div class="form-group mb-3 col-md-4 col-6">
      <label for="{sup.id}_rut" class="form-label">RUT</label>
      <input type="text" id="{sup.id}_rut" placeholder="11111111-1" class="form-control" bind:value={sup.rut}>
    </div>
  
    <div class="form-group mb-3 col-6">
      <label for="{sup.id}_telefono" class="form-label">Teléfono</label>
      <input type="text" id="{sup.id}_telefono" class="form-control" bind:value={sup.telefono}>
    </div>
  
    <div class="form-group mb-3 col-md-6 col-12">
      <label for="{sup.id}_email" class="form-label">Correo Electrónico</label>
      <input type="email" id="{sup.id}_email" class="form-control" bind:value={sup.email}>
    </div>
  
    <div class="form-group mb-3 col-12">
      <label for="{sup.id}_direccion" class="form-label">Dirección</label>
      <input type="text" id="{sup.id}_direccion" class="form-control" bind:value={sup.direccion}>
    </div>
  </div>
  {/each}
  
  <div>
    <button type="button" class="btn btn-primary" on:click={() => addSuplente()}>Añadir suplente para retiro</button>
  </div>
</section>