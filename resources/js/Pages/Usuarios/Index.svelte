<script>
	import Layout from '../../layouts/layout.svelte';
	import UserCard from './Partials/UserCard.svelte';
	export let users, roles, errors;

	$: errors && handleErrors();

	function handleErrors() {
		if (errors.length) console.log(errors);
	}

	function handleUserDelete(event) {
		const id = event.detail.id;
		users = users.filter((user) => user.id != id);
	}

	function handleUserUpdated(event) {
		const updated = event.detail.user;
		const user = users.findIndex((user) => user.id === updated.id);
		users[user] = updated;
	}
</script>

<Layout title="Usuarios">
	<section class="d-flex gap-3 mb-3 justify-content-center">
		<a class="btn btn-primary" href="/usuarios">Todos</a>
		{#each roles as rol}
			<a class="btn btn-primary" href="/usuarios?q={rol.name}">Usuarios {rol.name}</a>
		{/each}
		<a class="btn btn-primary" href="/usuarios?q=eliminados">Usuarios eliminados</a>
	</section>
	<section class="d-flex flex-column gap-2 mt-3">
		<div class="grid-users text-center fw-bold">
			<p>Nombre</p>
			<p>Correo</p>
			<p>Roles</p>
			<p>Opciones</p>
		</div>
		{#each users as user (user.id)}
			<UserCard
				{user}
				{roles}
				on:userDeleted={handleUserDelete}
				on:userUpdated={handleUserUpdated}
			/>
		{/each}
	</section>
	<slot />
</Layout>

<style>
	.grid-users {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
	}
</style>
