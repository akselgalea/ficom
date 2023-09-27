<script>
    import Layout from "../layouts/layout.svelte";
    import imgLogo from '../assets/img/logo.png'

    export let errors;

    $: errors && handleErrors();

	function handleErrors() {
		if (errors.length) console.log(errors);
	}
</script>

<Layout title="Bienvenida">
    <section class="welcome">
        <h1>Bienvenido!</h1>
        <img src={imgLogo} class="app-logo" alt="logo escuela" />
    </section>

    <slot/>
</Layout>


<style>
    .welcome {
        height: 50vh;
        display: grid;
        place-items: center;
        place-content: center;
    }

    .welcome h1 {
        font-size: 4rem;
        font-weight: bolder;
        color: #3433AA;
    }

    .app-logo {
        width: 100%;
    }
</style>