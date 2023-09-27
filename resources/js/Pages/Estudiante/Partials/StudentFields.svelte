<script>
	export let cursos,
		estudiante,
		errors,
		disabled = false;

</script>

<div class="row">
	<h2 class="form-title">Estudiante</h2>

	<div class="form-group mb-3 col-md-12 col-12">
		<label for="nivel" class="form-label">Nivel</label>
		<select
			id="nivel"
			name="nivel"
			bind:value={estudiante.nivel}
			class="form-control form-select {errors.nivel ? 'is-invalid' : ''}"
			{disabled}
		>
			<option value="" selected disabled hidden>Selecciona una opción</option>
			{#each cursos as curso}
				<option value={curso.id} selected={estudiante.nivel == curso.id}
					>{curso.curso + '-' + curso.paralelo}</option
				>
			{/each}
		</select>

		{#if errors.nivel}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.nivel[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-3 col-6">
		<label for="apellido_paterno" class="form-label">Apellido Paterno</label>
		<input
			type="text"
			id="apellido_paterno"
			name="apellido_paterno"
			bind:value={estudiante.apellido_paterno}
			class="form-control {errors.apellido_paterno ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.apellido_paterno}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.apellido_paterno[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-3 col-6">
		<label for="apellido_materno" class="form-label">Apellido Materno</label>
		<input
			type="text"
			id="apellido_materno"
			name="apellido_materno"
			bind:value={estudiante.apellido_materno}
			class="form-control {errors.apellido_materno ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.apellido_materno}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.apellido_materno[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-6 col-6">
		<label for="nombres" class="form-label">Nombres</label>
		<input
			type="text"
			id="nombres"
			name="nombres"
			bind:value={estudiante.nombres}
			class="form-control {errors.nombres ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.nombres}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.nombres[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-4 col-6">
		<label for="run" class="form-label">RUN</label>
		<input
			type="text"
			id="run"
			name="run"
			bind:value={estudiante.run}
			placeholder="11111111-1"
			class="form-control {errors.run ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.run}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.run[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-4 col-6">
		<label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
		<input
			type="date"
			id="fecha_nacimiento"
			name="fecha_nacimiento"
			bind:value={estudiante.fecha_nacimiento}
			class="form-control {errors.fecha_nacimiento ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.fecha_nacimiento}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.fecha_nacimiento[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-4 col-6">
		<label for="prioridad" class="form-label">Prioridad</label>
		<select
			id="prioridad"
			name="prioridad"
			bind:value={estudiante.prioridad}
			class="form-control form-select {errors.prioridad ? 'is-invalid' : ''}"
			{disabled}
		>
			<option value="" selected disabled hidden>Selecciona una opción</option>
			<option selected={estudiante.prioridad == 'alumno regular'} value="alumno regular"
				>Alumno regular</option
			>
			<option selected={estudiante.prioridad == 'nuevo prioritario'} value="nuevo prioritario"
				>Nuevo prioritario</option
			>
			<option selected={estudiante.prioridad == 'prioritario'} value="prioritario"
				>Prioritario</option
			>
		</select>

		{#if errors.prioridad}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.prioridad[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-8 col-6">
		<label for="email" class="form-label">Correo electrónico</label>
		<input
			type="email"
			id="email"
			name="email"
			bind:value={estudiante.email}
			placeholder="ejemplo@ejemplo.cl"
			class="form-control {errors.email ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.email}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.email[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-2 col-6">
		<label for="edad" class="form-label">Edad</label>
		<input
			type="number"
			id="edad"
			name="edad"
			bind:value={estudiante.edad}
			placeholder="14"
			class="form-control {errors.edad ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.edad}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.edad[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-2 col-6">
		<label for="genero" class="form-label">Género</label>
		<select
			id="genero"
			name="genero"
			bind:value={estudiante.genero}
			class="form-control form-select {errors.genero ? 'is-invalid' : ''}"
			{disabled}
		>
			<option value="" selected disabled hidden>Selecciona una opción</option>
			<option selected={estudiante.genero == 'M'} value="M">Masculino</option>
			<option selected={estudiante.genero == 'F'} value="F">Femenino</option>
		</select>

		{#if errors.genero}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.genero[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-4 col-6">
		<label for="nacionalidad" class="form-label">Nacionalidad</label>
		<input
			type="text"
			id="nacionalidad"
			name="nacionalidad"
			bind:value={estudiante.nacionalidad}
			placeholder="Chilena"
			class="form-control {errors.nacionalidad ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.nacionalidad}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.nacionalidad[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-8 col-12">
		<label for="direccion" class="form-label">Dirección</label>
		<input
			type="text"
			id="direccion"
			name="direccion"
			bind:value={estudiante.direccion}
			placeholder="Tierra del Fuego #17, La Cruz, Quillota"
			class="form-control {errors.direccion ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.direccion}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.direccion[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-12 col-12">
		<label for="enfermedades" class="form-label">Enfermedades Contraindicaciones</label>
		<input
			type="text"
			id="enfermedades"
			name="enfermedades"
			bind:value={estudiante.enfermedades}
			placeholder="Asma"
			class="form-control {errors.enfermedades ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.enfermedades}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.enfermedades[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-6 col-6">
		<label for="persona_emergencia" class="form-label">En caso de emergencia remitir a</label>
		<input
			type="text"
			id="persona_emergencia"
			name="persona_emergencia"
			bind:value={estudiante.persona_emergencia}
			placeholder="Juan Pablo Perez Vargas"
			class="form-control {errors.persona_emergencia ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.persona_emergencia}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.persona_emergencia[0]}</strong>
			</span>
		{/if}
	</div>

	<div class="form-group mb-3 col-md-6 col-6">
		<label for="telefono_emergencia" class="form-label">N° Telefónico</label>
		<input
			type="text"
			id="telefono_emergencia"
			name="telefono_emergencia"
			bind:value={estudiante.telefono_emergencia}
			placeholder="+56 9 71727374"
			class="form-control {errors.telefono_emergencia ? 'is-invalid' : ''}"
			{disabled}
		/>

		{#if errors.telefono_emergencia}
			<span class="invalid-feedback" role="alert">
				<strong>{errors.telefono_emergencia[0]}</strong>
			</span>
		{/if}
	</div>
</div>
