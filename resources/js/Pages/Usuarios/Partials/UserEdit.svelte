<script>
	import axios from 'axios';
	import toast from 'svelte-french-toast';
	import { createEventDispatcher } from 'svelte';

	export let user, roles;
	let errors = {};

	const dispatch = createEventDispatcher();

	const form = {
		id: user.id,
		name: user.name,
		email: user.email,
		roles: user.roles.map((rol) => rol.name)
	};

	function handleSubmit() {
		axios.patch(`/usuarios/${user.id}`, form).then(
			(res) => {
				errors = {};
				toast.success('Usuario actualizado con éxito!');

				dispatch('userUpdated', {
					user: res.data.user
				});
			},
			(error) => {
				toast.error('Error al actualizar al usuario');
				errors = error.response.data.errors;
			}
		);
	}
</script>

<button
	type="button"
	class="btn btn-sm btn-primary"
	data-bs-toggle="modal"
	data-bs-target="#editModal{user.id}"
>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		width="16"
		height="16"
		fill="currentColor"
		class="bi bi-pencil"
		viewBox="0 0 16 16"
	>
		<path
			d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"
		/>
	</svg>
</button>

<div class="modal" tabindex="-1" id="editModal{user.id}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar usuario</h5>
				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="modal"
					aria-label="Close"
				/>
			</div>
			<form on:submit|preventDefault={handleSubmit}>
				<div class="modal-body text-start d-flex flex-column gap-3">
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
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
						>Cerrar</button
					>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>
