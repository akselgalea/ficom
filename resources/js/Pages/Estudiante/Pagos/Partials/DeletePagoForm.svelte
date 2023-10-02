<script>
	import { toast } from 'svelte-french-toast';
	import { toCLP } from '../../../../helpers/helpers';
	import EstudianteInfo from './EstudianteInfo.svelte';
    import { createEventDispatcher } from 'svelte';
    export let pago;
    let deleteModal, errors = {};
    const dispatch = createEventDispatcher();

    const form = {
        password: ''
    }

    const onDelete = () => {
        console.log(form);
        // hacer el llamado al back
        dispatch('deleted', {
            pagoId: pago.id,
            mes: pago.mes
        })

        toast.success('Pago eliminado con éxtio!');
    }
</script>
<button
    class="t-py-1 t-px-2 t-text-sm t-font-medium t-focus:outline-none t-rounded-lg t-border focus:t-z-10 focus:t-ring-4 focus:t-ring-gray-700 t-bg-gray-800 t-text-white t-border-gray-600 hover:t-bg-red-500"
    on:click={() => deleteModal.show()}
>
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-x" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M4 7h16" />
        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
        <path d="M10 12l4 4m0 -4l-4 4" />
    </svg>
</button>

<dialog bind:this={deleteModal} class="t-w-screen t-h-screen t-fixed t-bg-slate-900 t-top-0 t-left-0 t-bg-opacity-50 t-items-center">
    <div class="t-grid t-content-center t-place-items-center t-w-full t-h-full">
        <div class="card t-max-w-3xl t-w-5/6 t-p-7">
            <form on:submit|preventDefault={onDelete}>
                <header class="t-mb-7">
                    <h2 class="t-font-bold t-text-xl">Eliminar Pago</h2>
                    <p>
                        ¿Estás seguro de que deseas eliminarlo? Una vez eliminado el pago, toda la información sobre este
                        será <b>eliminada permanentemente</b> del sistema. Para confirmar ingresa a continuación tu <b>contraseña</b>.
                    </p>

                    <div>
                        N° Documento: {pago.num_documento} - Mes: {pago.mes} - Año: {pago.anio} - Monto: {toCLP(pago.valor)}
                    </div>
                </header>

                <div class="form-group">
                    <input type="password" name="password" class="form-control" bind:value={form.password} placeholder="Contraseña" />
            
                    {#if errors.password}
                        <span class="invalid-feedback" role="alert">
                            <strong>{errors.password[0]}</strong>
                        </span>
                    {/if}
                </div>

                <footer class="t-mt-7">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" on:click={() => deleteModal.close()}>Cancelar</button>
                </footer>
            </form>
        </div>
    </div>
</dialog>