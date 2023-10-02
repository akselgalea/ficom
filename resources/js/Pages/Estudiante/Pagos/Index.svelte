<script>
    import axios from 'axios';
    import Layout from '../../../layouts/layout.svelte';
	import EstudianteInfo from './Partials/EstudianteInfo.svelte';
    import NewPagoForm from './Partials/NewPagoForm.svelte';
	import PagosTable from './Partials/PagosTable.svelte';
    import toast from 'svelte-french-toast';

    export let estudiante, pagos, mensualidad, errors;

    $: if(errors.length) {
        console.error(errors);
    }
    
    let formErrors = {}

    const newPago = (data) => {
        axios.post(`/estudiantes/${estudiante.id}/pagos`, data).then((res) => {
            const {pago} = res.data;
            pagos[pago.mes] = pagos[pago.mes].concat(pago);
            toast.success(res.data.message);
        }, err => {
            const validationErrors = err.response.data.errors ?? null;

            if(validationErrors) {
                formErrors = err.response.data.errors;
            } else {
                toast.error(err.response.data.message)
            }
        })
    }
</script>

<Layout title="Pagos">
    <EstudianteInfo {estudiante} {mensualidad} />
    <NewPagoForm total={mensualidad} onSubmit={newPago} errors={formErrors} />
    <PagosTable {estudiante} {pagos} slot="nocard" />
</Layout>

<slot/>