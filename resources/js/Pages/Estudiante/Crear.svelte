<script>
  import toast, { Toaster } from 'svelte-french-toast';
  import { useForm } from '@inertiajs/svelte';
  import StudentFields from './Partials/StudentFields.svelte';
  import GuardianFields from './Partials/GuardianFields.svelte';
  import SubstituteFields from './Partials/SubstituteFields.svelte';
  import ParentFields from './Partials/ParentFields.svelte';
  import escudo from '../../assets/img/escudo.png';

  export let cursos, errors;

  $: errors && handleErrorsChange();
  
  let erroresEstudiante = {};
  let erroresApoderadoTitular = {};
  let erroresApoderadoSuplente = {};
  let erroresMadre = {};
  let erroresPadre = {};
  let erroresSuplentes = {};

  let form = useForm({
    estudiante: {
      nivel: '',
      apellido_paterno: '',
      apellido_materno: '',
      nombres: '',
      run: '',
      fecha_nacimiento: '',
      prioridad: '',
      email: '',
      edad: '',
      genero: '',
      nacionalidad: '',
      direccion: '',
      enfermedades: '',
      persona_emergencia: '',
      telefono_emergencia: ''
    },
    apoderado_titular: {
      nombre: '',
      rut: '',
      telefono: '',
      email: '',
      direccion: ''
    },
    apoderado_suplente: {
    },
    madre: {},
    padre: {},
    suplentes: []
  });

  let today = new Date();

  function handleSubmit() {
    $form.post('/estudiantes/crear', {
      preserveScroll: true,
      onSuccess: () => {
        toast.success('Estudiante guardado con éxito!');

        window.addEventListener("afterprint", (event) => {
          toast('Ahora que has guardado el documento\nse ha limpiado el formulario 👍');
          $form.reset()
        }, {once: true});

        setTimeout(() => {
          window.print();
        }, 2200);
      }
    });
  }

  function handleErrorsChange() {
    resetErrors();
    const keysEstudiante = Object.keys(errors).filter(key => key.startsWith("estudiante"));
    const keysApoderadoTitular = Object.keys(errors).filter(key => key.startsWith("apoderado_titular"));
    const keysApoderadoSuplente = Object.keys(errors).filter(key => key.startsWith("apoderado_suplente"));
    const keysMadre = Object.keys(errors).filter(key => key.startsWith("madre"));
    const keysPadre = Object.keys(errors).filter(key => key.startsWith("padre"));
    const keysSuplentes = Object.keys(errors).filter(key => key.startsWith("suplentes"));

    keysEstudiante.forEach(key => {
      erroresEstudiante[key.split('.')[1]] = errors[key];
    });

    keysApoderadoTitular.forEach(key => {
      erroresEstudiante[key.split('.')[1]] = errors[key];
    });

    keysApoderadoSuplente.forEach(key => {
      erroresEstudiante[key.split('.')[1]] = errors[key];
    });

    keysMadre.forEach(key => {
      erroresEstudiante[key.split('.')[1]] = errors[key];
    });

    keysPadre.forEach(key => {
      erroresEstudiante[key.split('.')[1]] = errors[key];
    });

    keysSuplentes.forEach(key => {
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
</script>

<svelte:head>
  <title>Crear estudiante - Simón Bolívar</title>
</svelte:head>

<Toaster />

<main class="main-container">
  <div class="container card">
    <form on:submit|preventDefault={handleSubmit}>
      <header class="mb-5 header-estudiante">
        <img height="120px" src={escudo} alt="escudo colegio simon bolivar">
        <div class="text-center">
          <h2>Pre-Matrícula { today.getFullYear() }</h2>
          <h3>Colegio Simón Bolívar</h3>
        </div>
        <div>
          <h5 class="text-center">Fecha:</h5>
          <h5 class="text-center">{ (new Intl.DateTimeFormat('es-CL').format(today)) }</h5>
        </div>
      </header>

      <StudentFields bind:estudiante={$form.estudiante} cursos={cursos} errors={erroresEstudiante} />
      <GuardianFields bind:apoderado={$form.apoderado_titular} type="titular" errors={erroresApoderadoTitular} />
      <GuardianFields bind:apoderado={$form.apoderado_suplente} type="suplente" errors={erroresApoderadoSuplente} />
      <ParentFields bind:parent={$form.madre} type="madre" errors={erroresMadre} />
      <ParentFields bind:parent={$form.padre} type="padre" errors={erroresPadre} />
      <SubstituteFields bind:suplentes={$form.suplentes} errors={erroresSuplentes} />
      
      <footer class="footer-estudiante">
        <p>Firma Apoderado</p>
        <p>Firma recepción</p>
      </footer>
      
      <div class="my-3 botones-formulario">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" on:click={() => window.print() }>Generar documento</button>
      </div>
    </form>
</main>
<slot></slot>
<style>
  .main-container {
    max-width: 1080px;
    margin: 0 auto;
    padding: 1rem;
  }
</style>