<script>
	export let apoderado = {
		nombre: '',
		rut: '',
		telefono: '',
		email: '',
		direccion: ''
	};

	export let type,
		errors,
		disabled = false;
</script>

<div class="row mt-3">
	<h2 class="form-title">Datos del apoderado {type}</h2>
	<div class="form-group mb-8 col-md-8 col-6">
		<label for="{type}_nombre" class="form-label">Nombre completo</label>
		<input
			type="text"
			id="{type}_nombre"
			class="form-control {errors.nombre ? 'is-invalid' : ''}"
			bind:value={apoderado.nombre}
			{disabled}
		/>

		{#if errors.nombre}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.nombre}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-4 col-6">
		<label for="{type}_rut" class="form-label">RUT</label>
		<input
			type="text"
			id="{type}_rut"
			placeholder="11111111-1"
			class="form-control {errors.rut ? 'is-invalid' : ''}"
			bind:value={apoderado.rut}
			{disabled}
		/>

		{#if errors.rut}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.rut}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-6">
		<label for="{type}_telefono" class="form-label">Teléfono</label>
		<input
			type="text"
			id="{type}_telefono"
			class="form-control {errors.telefono ? 'is-invalid' : ''}"
			bind:value={apoderado.telefono}
			{disabled}
		/>

		{#if errors.telefono}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.telefono}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-6 col-12">
		<label for="{type}_email" class="form-label">Correo Electrónico</label>
		<input
			type="email"
			id="{type}_email"
			class="form-control {errors.email ? 'is-invalid' : ''}"
			bind:value={apoderado.email}
			{disabled}
		/>

		{#if errors.email}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.email}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-12">
		<label for="{type}_direccion" class="form-label">Dirección</label>
		<input
			type="text"
			id="{type}_direccion"
			class="form-control {errors.direccion ? 'is-invalid' : ''}"
			bind:value={apoderado.direccion}
			{disabled}
		/>

		{#if errors.direccion}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.direccion}</strong>
			</span>
		{/if}
	</div>
</div>
