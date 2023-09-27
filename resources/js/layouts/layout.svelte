<script>
	import toast, { Toaster } from 'svelte-french-toast';
	export let title;

	const isWelcome = title === 'Bienvenida'
</script>

<svelte:head>
	<title>{title + ' - ' ?? ''}Simón Bolívar</title>
</svelte:head>

<Toaster />

<main class={`main-section p-4 ${ isWelcome ? 'waves-bg' : 'main-bg' }`}>
	<div class={`container ${ isWelcome ? '' : 'card' }`}>
		<slot />
	</div>
</main>

<style>
	.main-section {
		min-height: calc(100vh - 128px);
		margin: 0 auto;
		padding: 1rem;
	}

	.container {
		max-width: 1080px;
	}

	.main-bg {
		background-color: #4985FF;
	}

	.waves-bg {
		background-image: url('../assets/img/waves.svg');
		background-position: bottom;
		background-repeat: no-repeat;
	}
</style>
