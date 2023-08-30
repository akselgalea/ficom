<script>
	import axios from 'axios';
	import toast from 'svelte-french-toast';
	import Layout from '../../layouts/layout.svelte';

	export let roles;
	let errors = {};

	const defaultValues = {
		name: '',
		email: '',
		password: '',
		password_confirmation: '',
		roles: []
	};

	let form = { ...defaultValues };

	function handleSubmit() {
		axios.post(`/usuarios`, form).then(
			(res) => {
				errors = {};
				toast.success('Usuario creado con éxito!');
				form = { ...defaultValues };
			},
			(error) => {
				toast.error('Error al crear al usuario');
				errors = error.response.data.errors;
			}
		);
	}
</script>

<Layout title="Crear usuario">
	<header class="mb-1">
		<h2>Crear usuario</h2>
	</header>

	<form on:submit|preventDefault={handleSubmit}>
		<div class="d-flex flex-column gap-3">
			<div class="form-group">
				<label for="name" class="form-label fw-semibold">Nombre</label>
				<input
					type="text"
					id="name"
					class="form-control {errors.name ? 'is-invalid' : ''}"
					bind:value={form.name}
					autocomplete="off"
				/>

				{#if errors.name}
					<span class="invalid-feedback" role="alert">
						<strong>{errors.name}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group">
				<label for="email" class="form-label fw-semibold">Email</label>
				<input
					type="email"
					id="email"
					class="form-control {errors.email ? 'is-invalid' : ''}"
					bind:value={form.email}
					autocomplete="off"
				/>

				{#if errors.email}
					<span class="invalid-feedback" role="alert">
						<strong>{errors.email}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group">
				<label for="password" class="form-label fw-semibold">Password</label>
				<input
					type="password"
					id="password"
					class="form-control {errors.password ? 'is-invalid' : ''}"
					bind:value={form.password}
					autocomplete="off"
				/>

				{#if errors.password}
					<span class="invalid-feedback" role="alert">
						<strong>{errors.password}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group">
				<label for="confirm" class="form-label fw-semibold">Confirm password</label>
				<input
					type="password"
					id="confirm"
					class="form-control {errors.password_confirmation ? 'is-invalid' : ''}"
					bind:value={form.password_confirmation}
					autocomplete="off"
				/>
				{#if errors.password_confirmation}
					<span class="invalid-feedback" role="alert">
						<strong>{errors.password_confirmation}</strong>
					</span>
				{/if}
			</div>

			<div class="form-group">
				<p class="form-label fw-semibold">Roles</p>
				<div class="d-flex gap-3 text-capitalize">
					{#each roles as role (role.id)}
						<div class="form-check">
							<input
								class="form-check-input"
								type="checkbox"
								id={role.name}
								name={role.name}
								value={role.name}
								autocomplete="off"
								bind:group={form.roles}
							/>
							<label class="form-check-label" for={role.name}>
								{role.name}
							</label>
						</div>
					{/each}
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-primary mt-3">Guardar</button>
	</form>
	<slot />
</Layout>
