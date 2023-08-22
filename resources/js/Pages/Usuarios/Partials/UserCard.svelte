<script>
	import UserEdit from './UserEdit.svelte';
	import UserDelete from './UserDelete.svelte';
	export let user, roles;

	const formatter = new Intl.ListFormat('es', { style: 'long', type: 'conjunction' });

	const formatList = (list) => {
		if (list.length == 0) return 'Ninguno';

		const values = list.map((item) => {
			return item.name;
		});

		return formatter.format(values);
	};
</script>

<div class="card grid-users text-center py-1 px-2 gap-2 fw-medium">
	<p>{user.name}</p>
	<p>{user.email}</p>
	<p>{formatList(user.roles)}</p>
	<div>
		<UserEdit {user} {roles} on:userUpdated />
		<UserDelete {user} on:userDeleted />
	</div>
</div>

<style>
	.grid-users {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
		align-items: center;
	}

	.grid-users p {
		margin: 0;
	}

	.grid-users::-webkit-scrollbar {
		display: none;
	}

	/* Hide scrollbar for IE, Edge and Firefox */
	.grid-users {
		-ms-overflow-style: none; /* IE and Edge */
		scrollbar-width: none; /* Firefox */
	}
</style>
