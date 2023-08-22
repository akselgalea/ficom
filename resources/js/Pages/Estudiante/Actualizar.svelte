<script>
	import { onMount } from 'svelte';
	import { useForm } from '@inertiajs/svelte';
	import toast from 'svelte-french-toast';
	import StudentFields from './Partials/StudentFields.svelte';
	import GuardianFields from './Partials/GuardianFields.svelte';
	import SubstituteFields from './Partials/SubstituteFields.svelte';
	import ParentFields from './Partials/ParentFields.svelte';
	import escudo from '../../assets/img/escudo.png';
	import Layout from '../../layouts/layout.svelte';
	export let cursos, errors, estudiante;

	$: errors && handleErrorsChange();

	const nivel = estudiante.nivel;

	let erroresEstudiante = {};
	let erroresApoderadoTitular = {};
	let erroresApoderadoSuplente = {};
	let erroresMadre = {};
	let erroresPadre = {};
	let erroresSuplentes = {};

	let form = useForm({
		estudiante: estudiante,
		apoderado_titular: estudiante.apoderados.apoderado_titular ?? {},
		apoderado_suplente: estudiante.apoderados.apoderado_suplente ?? {},
		madre: estudiante.apoderados.madre ?? {},
		padre: estudiante.apoderados.padre ?? {},
		suplentes: estudiante.apoderados.suplentes
	});

	let editing = false;
	let today = new Date();

	onMount(() => {
		let inputs = document.querySelectorAll('.form-control');
		inputs.forEach((input) => {
			input.addEventListener(
				'input',
				() => {
					input.classList.remove('is-invalid');
				},
				{ once: true }
			);
		});
	});

	function handleSubmit() {
		$form.post(`/estudiantes/${estudiante.id}/editar`, {
			preserveScroll: true,
			onSuccess: (res) => {
				toast.success('Estudiante actualizado con éxito!');

				window.addEventListener('afterprint', (event) => {
					toggleEdit();
				});

				if ($form.estudiante.nivel != nivel) {
					setTimeout(() => {
						window.print();
					}, 2200);
				}
			}
		});
	}

	function handleErrorsChange() {
		resetErrors();
		const keysEstudiante = Object.keys(errors).filter((key) => key.startsWith('estudiante'));
		const keysApoderadoTitular = Object.keys(errors).filter((key) =>
			key.startsWith('apoderado_titular')
		);
		const keysApoderadoSuplente = Object.keys(errors).filter((key) =>
			key.startsWith('apoderado_suplente')
		);
		const keysMadre = Object.keys(errors).filter((key) => key.startsWith('madre'));
		const keysPadre = Object.keys(errors).filter((key) => key.startsWith('padre'));
		const keysSuplentes = Object.keys(errors).filter((key) => key.startsWith('suplentes'));

		keysEstudiante.forEach((key) => {
			erroresEstudiante[key.split('.')[1]] = errors[key];
		});

		keysApoderadoTitular.forEach((key) => {
			erroresEstudiante[key.split('.')[1]] = errors[key];
		});

		keysApoderadoSuplente.forEach((key) => {
			erroresEstudiante[key.split('.')[1]] = errors[key];
		});

		keysMadre.forEach((key) => {
			erroresEstudiante[key.split('.')[1]] = errors[key];
		});

		keysPadre.forEach((key) => {
			erroresEstudiante[key.split('.')[1]] = errors[key];
		});

		keysSuplentes.forEach((key) => {
			erroresEstudiante[key.split('.')[1]] = errors[key];
		});
	}

	function resetErrors() {
		erroresEstudiante = {};
		erroresApoderadoTitular = {};
		erroresApoderadoSuplente = {};
		erroresMadre = {};
		erroresPadre = {};
		erroresSuplentes = {};
	}

	function toggleEdit() {
		editing = !editing;
	}
</script>

<svelte:head>
	<title>Perfil estudiante - Simón Bolívar</title>
</svelte:head>

<Layout>
	<div class="buttons mb-4 print-hidden">
		<a href="/estudiantes/{estudiante.id}/pagos" class="btn btn-primary"
			>Ver historial de pago</a
		>
		<a href="/estudiantes/{estudiante.id}/becas" class="btn btn-primary">Administrar beca</a>
	</div>

	<form on:submit|preventDefault={handleSubmit}>
		{#if editing}
			<header class="mb-5 header-estudiante">
				<img height="120px" src={escudo} alt="escudo colegio simon bolivar" />
				<div class="text-center">
					<h2>Pre-Matrícula {today.getFullYear()}</h2>
					<h3>Colegio Simón Bolívar</h3>
				</div>
				<div>
					<h5 class="text-center">Fecha:</h5>
					<h5 class="text-center">
						{new Intl.DateTimeFormat('es-CL').format(today)}
					</h5>
				</div>
			</header>
		{/if}

		<StudentFields
			bind:estudiante={$form.estudiante}
			{cursos}
			errors={erroresEstudiante}
			disabled={!editing}
		/>

		<GuardianFields
			bind:apoderado={$form.apoderado_titular}
			type="titular"
			errors={erroresApoderadoTitular}
			disabled={!editing}
		/>

		<GuardianFields
			bind:apoderado={$form.apoderado_suplente}
			type="suplente"
			errors={erroresApoderadoSuplente}
			disabled={!editing}
		/>

		<ParentFields
			bind:parent={$form.madre}
			type="madre"
			errors={erroresMadre}
			disabled={!editing}
		/>

		<ParentFields
			bind:parent={$form.padre}
			type="padre"
			errors={erroresPadre}
			disabled={!editing}
		/>

		<SubstituteFields
			bind:suplentes={$form.suplentes}
			errors={erroresSuplentes}
			disabled={!editing}
		/>

		{#if editing}
			<footer class="footer-estudiante">
				<p>Firma Apoderado</p>
				<p>Firma recepción</p>
			</footer>
		{/if}

		<div class="my-3 botones-formulario">
			<button
				type="button"
				class="btn btn-secondary"
				hidden={editing}
				on:click={() => toggleEdit()}>Editar</button
			>
			<button
				type="button"
				class="btn btn-danger"
				hidden={!editing}
				on:click={() => toggleEdit()}>Cancelar</button
			>

			<button type="submit" class="btn btn-primary" hidden={!editing}>Guardar</button>
			<button
				type="button"
				class="btn btn-secondary"
				on:click={() => window.print()}
				hidden={!editing}>Generar documento</button
			>
		</div>
	</form>

	<slot />
</Layout>
