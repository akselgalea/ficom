<script>
    import { documentoOptions } from './../../../../helpers/const.js';
    import { createEventDispatcher } from 'svelte';
    export let pago;

    let errors = {}
    let editModal;

    const dispatch = createEventDispatcher();
    const form = {...pago}

    const updatePago = () => {
        dispatch('updated', {
            newPago: form,
            oldPago: pago
        })
    }
</script>

<button
    class="t-py-1 t-px-2 t-text-sm t-font-medium t-focus:outline-none t-rounded-lg t-border focus:t-z-10 focus:t-ring-4 focus:t-ring-gray-700 t-bg-gray-800 t-text-white t-border-gray-600 hover:t-bg-gray-700"
    on:click={() => editModal.show()}
>
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
        <path d="M16 5l3 3" />
    </svg>
</button>

<dialog bind:this={editModal} class="t-w-screen t-h-screen t-fixed t-bg-slate-900 t-top-0 t-left-0 t-bg-opacity-50 t-items-center">
    <div class="t-grid t-content-center t-place-items-center t-w-full t-h-full">
        <div class="card t-max-w-6xl t-w-5/6 t-p-7">
            <form on:submit|preventDefault={updatePago} class="w-full">
                <header class="t-mb-7">
                    <h2 class="t-font-bold t-text-xl">Actualizar Pago</h2>
                </header>

                <div class="row">
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="mes" class="form-label t-font-semibold">Mes</label>
                        <!-- agregar el is-invalid -->
                        <select name="mes" class="form-control form-select {errors.mes ? 'is-invalid' : ''}" bind:value={form.mes}>
                            <option value="" disabled hidden>Selecciona una opción</option>
                            <option value="matricula">Matrícula</option>
                            <option value="marzo">Marzo</option>
                            <option value="abril">Abril</option>
                            <option value="mayo">Mayo</option>
                            <option value="junio">Junio</option>
                            <option value="julio">Julio</option>
                            <option value="agosto">Agosto</option>
                            <option value="septiembre">Septiembre</option>
                            <option value="octubre">Octubre</option>
                            <option value="noviembre">Noviembre</option>
                            <option value="diciembre">Diciembre</option>
                        </select>
                
                        {#if errors.mes}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.mes[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="anio" class="form-label t-font-semibold">Año</label>
                        <select name="anio" class="form-control form-select {errors.anio ? 'is-invalid' : ''}" bind:value={form.anio}>
                            <option value="" selected disabled hidden>Selecciona una opción</option>
                            <option value={2022}>2022</option>
                            <option value={2023}>2023</option>
                            <option value={2024}>2024</option>
                            <option value={2025}>2025</option>
                            <option value={2026}>2026</option>
                            <option value={2027}>2027</option>
                        </select>
                
                        {#if errors.anio}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.anio[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="documento" class="form-label t-font-semibold">Documento</label>
                        <select 
                            name="documento"
                            class="form-control form-select {errors.documento ? 'is-invalid' : ''}"
                            bind:value={form.documento}
                            on:change={() => form.forma = ''}
                        >
                            <option value="" selected disabled hidden>Selecciona una opción</option>
                            <option value="boleta">Boleta</option>
                            <option value="recibo">Recibo</option>
                        </select>
                
                        {#if errors.documento}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.documento[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="num_documento" class="form-label t-font-semibold">N° Documento</label>
                        <input 
                            type="number"
                            name="num_documento"
                            class="form-control {errors.num_documento ? 'is-invalid' : ''}"
                            bind:value={form.num_documento}
                            placeholder="0"
                        >
                
                        {#if errors.num_documento}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.num_documento[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="fecha_pago" class="form-label t-font-semibold">Fecha</label>
                        <input type="date" name="fecha_pago" class="form-control {errors.fecha_pago ? 'is-invalid' : ''}" bind:value={form.fecha_pago}>
                
                        {#if errors.fecha_pago}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.fecha_pago[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="valor" class="form-label t-font-semibold">Monto</label>
                        <input type="number" name="valor" class="form-control {errors.valor ? 'is-invalid' : ''}" bind:value={form.valor}>
                
                        {#if errors.valor}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.valor[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-6 col-md-4">
                        <label for="forma" class="form-label t-font-semibold">Forma de pago</label>
                        <select name="forma" class="form-control form-select text-capitalize {errors.forma ? 'is-invalid' : ''}" bind:value={form.forma}>
                            <option value="" selected disabled hidden>Selecciona una opción</option>
                            {#each documentoOptions[form.documento] as option }
                            <option value={option} class="text-capitalize">{option}</option>
                            {/each}
                        </select>
                
                        {#if errors.forma}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.forma[0]}</strong>
                            </span>
                        {/if}
                    </div>
                    <div class="form-group mb-3 col-12">
                        <label for="observacion" class="form-label t-font-semibold">Observaciones</label>
                        <textarea 
                            name="observacion"
                            class="form-control {errors.observacion ? 'is-invalid' : ''}"
                            placeholder="Observación..."
                        >{form.observacion}</textarea>
                
                        {#if errors.observacion}
                            <span class="invalid-feedback" role="alert">
                                <strong>{errors.observacion[0]}</strong>
                            </span>
                        {/if}
                    </div>
            
                </div>

                <footer class="t-mt-7">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" on:click={() => editModal.close()}>Cancelar</button>
                </footer>
            </form>
        </div>
    </div>
</dialog>