<script>
	import toast, { Toaster } from 'svelte-french-toast';
	export let title;
</script>

<svelte:head>
	<title>{title + ' - ' ?? ''}Simón Bolívar</title>
</svelte:head>

<Toaster />

<main class="main-container">
	<div class="container card">
		<slot />
	</div>
</main>

<style>
	.main-container {
		max-width: 1080px;
		margin: 0 auto;
		padding: 1rem;
	}
</style>
