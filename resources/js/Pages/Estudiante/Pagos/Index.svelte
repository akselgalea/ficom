<script>
    import axios from 'axios';
    import Layout from '../../../layouts/layout.svelte';
	import EstudianteInfo from './Partials/EstudianteInfo.svelte';
    import NewPagoForm from './Partials/NewPagoForm.svelte';
	import PagosTable from './Partials/PagosTable.svelte';

    export let estudiante, pagos, mensualidad, errors;

    $: if(errors.length) {
        console.error(errors);
    }
    
    let formErrors = {}

    const newPago = (data) => {
        axios.post(`/estudiantes/${estudiante.id}/pagos`, data).then((res) => {
            // console.log(res);
        }, err => {
            formErrors = err.response.data.errors;
        })
    }
</script>

<Layout title="Pagos">
    <EstudianteInfo {estudiante} {mensualidad} />
    <NewPagoForm total={mensualidad} onSubmit={newPago} errors={formErrors} />
    <PagosTable {estudiante} {pagos} slot="nocard" />
</Layout>