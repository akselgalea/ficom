<script>
	import Layout from '../../layouts/layout.svelte';
	export let registros, errors;

	$: errors && handleErrors();

	function handleErrors() {
		if (errors.length) console.log(errors);
	}

	const formatter = new Intl.ListFormat('es', { style: 'long', type: 'conjunction' });

	const formatList = (list) => {
		if (list.length == 0) return 'Ninguno';

		const values = list.map((item) => {
			return item.name;
		});

		return formatter.format(values);
	};

	const printData = (data) => {
		if (data.length == 0) return 'Sin datos';

		return JSON.stringify(data);
	};
</script>

<Layout title="Bitácora">
	<section class="d-flex gap-3 mb-3 justify-content-center">
		<a class="btn btn-primary" href="/usuarios/bitacora">Todos</a>
		<a class="btn btn-primary" href="/usuarios/bitacora?action=POST">POST</a>
		<a class="btn btn-primary" href="/usuarios/bitacora?action=PATCH">PATCH</a>
		<a class="btn btn-primary" href="/usuarios/bitacora?action=DELETE">DELETE</a>
	</section>
	<section class="d-flex flex-column gap-2 mt-3">
		<div class="grid-registros text-center fw-bold">
			<p>URL</p>
			<p>Método</p>
			<p>Estatus</p>
			<p>Datos</p>
			<p>Usuario</p>
			<p>Rol</p>
		</div>

		{#each registros as reg}
			<div class="grid-registros text-center">
				<p>{reg.path}</p>
				<p>{reg.method}</p>
				<p>{reg.status}</p>
				<p class="text-nowrap overflow-hidden ellipsis">{printData(reg.data)}</p>
				<p>{reg.user_and_role.name}</p>
				<p class="text-capitalize">{formatList(reg.user_and_role.roles)}</p>
			</div>
		{/each}
	</section>
	<slot />
</Layout>

<style>
	.grid-registros {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
	}

	.ellipsis {
		text-overflow: ellipsis;
	}
</style>
