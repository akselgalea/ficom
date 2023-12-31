<script>
	import { onMount } from 'svelte';
	import Layout from '../../layouts/layout.svelte';
	import Pagination from '../../components/Pagination.svelte';

	export let errors, estudiantes, cursos;

	const urlParams = new URLSearchParams(window.location.search);
	let search, curso, page, perPage;

	onMount(() => {
		perPage = urlParams.get('perPage');
		search = urlParams.get('search');
		curso = Number(urlParams.get('curso')) || 'todos';
		page = urlParams.get('page') ?? '1';
	});

	$: errors && handleErrors();

	function handleErrors() {
		if (errors.length) console.log(errors);
	}

	const prioridadClasses = {
		'alumno regular': 'table-light',
		prioritario: 'table-primary',
		'nuevo prioritario': 'table-danger'
	};
</script>

<Layout title="Estudiantes">
	<div id="table">
		<div class="buttons mb-3">
			<a href="/estudiantes/generar-reporte" class="btn btn-primary"
				>Generar reporte FICOM de todos los estudiantes</a
			>
			<a href="/estudiantes/registro" class="btn btn-primary">Generar reporte Registro Escolar</a>
		</div>
		<form class="buscador">
			<div>
				<select class="form-select" name="perPage" id="perPage" bind:value={perPage}>
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>
				</select>
			</div>
			<div>
				<select class="form-select" name="curso" id="curso-select" bind:value={curso}>
					<option selected value="todos">Todos</option>
					{#each cursos as curso}
						<option value={curso.id}>{curso.curso}-{curso.paralelo}</option>
					{/each}
				</select>
			</div>
			<div>
				<input
					class="form-control me-2"
					name="search"
					type="search"
					placeholder="Buscar"
					aria-label="Buscar"
					bind:value={search}
				/>
			</div>
			<button class="btn btn-outline-dark" type="submit">Buscar</button>
		</form>
		<section class="seccion-tabla">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">Apellidos</th>
						<th scope="col">Nombre</th>
						<th scope="col">RUN</th>
						<th scope="col">Prioridad</th>
						<th scope="col">Curso</th>
						<th scope="col">Opciones</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
					{#each estudiantes.data as estud}
						<tr class={prioridadClasses[estud.periodo_actual.prioridad]}>
							<td>{estud.apellidos}</td>
							<td>{estud.nombres}</td>
							<td>{estud.rut}-{estud.dv}</td>
							<td class="flc">{estud.periodo_actual.prioridad}</td>
							<td>{estud.periodo_actual.curso ? `${estud.periodo_actual.curso.curso}-${estud.periodo_actual.curso.paralelo}` : ''}</td>
							<td>
								<a
									href="/estudiantes/{estud.id}"
									class="btn btn-primary"
									data-bs-toggle="tooltip"
									title="Perfil del estudiante"
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										width="16"
										height="16"
										fill="currentColor"
										class="bi bi-person-fill"
										viewBox="0 0 16 16"
									>
										<path
											d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
										/>
									</svg>
								</a>
								<a
									href="/estudiantes/{estud.id}/pagos"
									class="btn btn-secondary"
									data-bs-toggle="tooltip"
									title="Pagos del estudiante"
								>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										width="16"
										height="16"
										fill="currentColor"
										class="bi bi-card-list"
										viewBox="0 0 16 16"
									>
										<path
											d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"
										/>
										<path
											d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"
										/>
									</svg>
								</a>
							</td>
						</tr>
					{/each}
				</tbody>
			</table>
			
			<Pagination links={estudiantes.links} {page} />
		</section>
	</div>
</Layout>

<slot />

<style>
	tr {
		vertical-align: middle;
	}

	.buscador {
		width: 100%;
		display: flex;
		justify-content: flex-end;
		gap: 0.5rem;
		margin-bottom: 1rem;
	}

	.seccion-tabla {
		display: flex;
		flex-direction: column;
		min-height: 500px;
		gap: 1rem;
		justify-content: space-between;
		align-items: center;
	}
</style>
