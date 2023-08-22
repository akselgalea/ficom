<script>
	import axios from 'axios';
	import toast from 'svelte-french-toast';
	import { createEventDispatcher } from 'svelte';
	export let user;

	const dispatch = createEventDispatcher();
	let input = '';

	function handleDelete() {
		axios.delete(`/usuarios/${user.id}`).then(
			(res) => {
				console.log(res);
				toast.success('Usuario eliminado satisfactoriamente!');
				dispatch('userDeleted', {
					id: user.id
				});

				document.getElementById(`deleteModalClose${user.id}`).click();
			},
			(error) => {
				toast.error('Ha ocurrido un error al intentar eliminar al usuario!');
				console.error(error);
			}
		);
	}
</script>

<button
	type="button"
	class="btn btn-sm btn-danger"
	data-bs-toggle="modal"
	data-bs-target="#deleteModal{user.id}"
>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		width="16"
		height="16"
		fill="currentColor"
		class="bi bi-trash"
		viewBox="0 0 16 16"
	>
		<path
			d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"
		/>
		<path
			d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"
		/>
	</svg>
</button>

<div class="modal" tabindex="-1" id="deleteModal{user.id}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Eliminar usuario</h5>
				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="modal"
					aria-label="Close"
					id="deleteModalClose{user.id}"
				/>
			</div>
			<div class="modal-body">
				<p class="text-start m-0">
					Estás a punto de eliminar al usuario {user.name}.
					<br />Escribe <b>{user.name.split(' ')[0]}</b> para confirmar su eliminación.
				</p>
				<input
					type="text"
					name="name"
					class="form-control mt-2"
					bind:value={input}
					autocomplete="off"
				/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
					>Cancelar</button
				>
				<button type="button" on:click={() => handleDelete()} class="btn btn-danger"
					>Eliminar</button
				>
			</div>
		</div>
	</div>
</div>
