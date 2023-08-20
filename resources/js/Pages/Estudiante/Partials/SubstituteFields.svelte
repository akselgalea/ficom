<script>
	export let suplentes = [],
		errors,
		disabled = false;

	let counter = 0;

	function addSuplente() {
		counter++;

		suplentes = [
			...suplentes,
			{
				id: `s${counter}`,
				nombre: '',
				rut: '',
				telefono: '',
				email: '',
				direccion: ''
			}
		];
	}

	function removeSuplente(id) {
		if (confirm('¿Estás seguro de que deseas eliminar a este suplente?')) {
			suplentes = suplentes.filter((s) => s.id !== id);
		}
	}
</script>

<section class="mt-3 row print-hidden">
	{#each suplentes as sup, index (index)}
		<div class="row mt-3">
			<div class="d-flex justify-content-between">
				<h2 class="form-title">Datos del suplente para retiro {index + 1}</h2>
				<button
					type="button"
					class="btn btn-danger"
					on:click={() => removeSuplente(sup.id)}
					{disabled}>Eliminar</button
				>
			</div>

			<div class="form-group mb-8 col-md-8 col-6">
				<label for="{sup.id}_nombre" class="form-label">Nombre completo</label>
				<input
					type="text"
					id="{sup.id}_nombre"
					class="form-control {errors[`${index}.nombre`] ? 'is-invalid' : ''}"
					bind:value={sup.nombre}
					{disabled}
				/>

				{#if errors[`${index}.nombre`]}
					<span class="invalid-feedback" role="alert">
						<strong>{errors[`${index}.nombre`]}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group mb-3 col-md-4 col-6">
				<label for="{sup.id}_rut" class="form-label">RUT</label>
				<input
					type="text"
					id="{sup.id}_rut"
					placeholder="11111111-1"
					class="form-control {errors[`${index}.rut`] ? 'is-invalid' : ''}"
					bind:value={sup.rut}
					{disabled}
				/>

				{#if errors[`${index}.rut`]}
					<span class="invalid-feedback" role="alert">
						<strong>{errors[`${index}.rut`]}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group mb-3 col-6">
				<label for="{sup.id}_telefono" class="form-label">Teléfono</label>
				<input
					type="text"
					id="{sup.id}_telefono"
					class="form-control {errors[`${index}.telefono`] ? 'is-invalid' : ''}"
					bind:value={sup.telefono}
					{disabled}
				/>

				{#if errors[`${index}.telefono`]}
					<span class="invalid-feedback" role="alert">
						<strong>{errors[`${index}.telefono`]}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group mb-3 col-md-6 col-12">
				<label for="{sup.id}_email" class="form-label">Correo Electrónico</label>
				<input
					type="email"
					id="{sup.id}_email"
					class="form-control {errors[`${index}.email`] ? 'is-invalid' : ''}"
					bind:value={sup.email}
					{disabled}
				/>

				{#if errors[`${index}.email`]}
					<span class="invalid-feedback" role="alert">
						<strong>{errors[`${index}.email`]}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group mb-3 col-12">
				<label for="{sup.id}_direccion" class="form-label">Dirección</label>
				<input
					type="text"
					id="{sup.id}_direccion"
					class="form-control {errors[`${index}.direccion`] ? 'is-invalid' : ''}"
					bind:value={sup.direccion}
					{disabled}
				/>

				{#if errors[`${index}.direccion`]}
					<span class="invalid-feedback" role="alert">
						<strong>{errors[`${index}.direccion`]}</strong>
					</span>
				{/if}
			</div>
		</div>
	{/each}

	<div>
		<button type="button" class="btn btn-primary" on:click={() => addSuplente()} {disabled}
			>Añadir suplente para retiro</button
		>
	</div>
</section>
