<script>
    import toast from 'svelte-french-toast';
    import { createEventDispatcher } from 'svelte';
	import { documentoOptions } from './../../../../helpers/const.js';
    import { monthToString, formatDate } from "../../../../helpers/helpers";
	import axios from 'axios';
    export let onSubmit, estudianteId, errors;

    const dispatch = createEventDispatcher();
    
    let today = new Date();

    const form = {
        mes: monthToString(today.getMonth()),
        anio: today.getFullYear(),
        documento: 'boleta',
        num_documento: null,
        fecha_pago: formatDate(today),
        valor: 0,
        forma: '',
        observacion: ''
    }

    const yearChanged = () => {
        const year = form.anio;

        axios.get(`/estudiantes/${estudianteId}/pagos/periodo/${year}`).then(res => {
            const { data } = res;

            dispatch('yearChanged', {
                year,
                pagos: data.pagos,
                mensualidad: data.mensualidad,
                periodoAnterior: data.periodoAnterior
            });
        }, error => {
            console.log(error);
            toast.error(`No se pudo obtener los datos de pago del año ${year}`);
        })
    }
</script>

<form id="formPago" class="mt-3 row" on:submit|preventDefault={onSubmit(form)}>
    <h2>Registrar pago</h2>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="mes" class="form-label">Mes</label>
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
        <label for="anio" class="form-label">Año</label>
        <select name="anio" class="form-control form-select {errors.anio ? 'is-invalid' : ''}" bind:value={form.anio} on:change={yearChanged}>
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
        <label for="documento" class="form-label">Documento</label>
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
        <label for="num_documento" class="form-label">N° Documento</label>
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
        <label for="fecha_pago" class="form-label">Fecha</label>
        <input type="date" name="fecha_pago" class="form-control {errors.fecha_pago ? 'is-invalid' : ''}" bind:value={form.fecha_pago}>

        {#if errors.fecha_pago}
            <span class="invalid-feedback" role="alert">
                <strong>{errors.fecha_pago[0]}</strong>
            </span>
        {/if}
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="valor" class="form-label">Monto</label>
        <input type="number" name="valor" class="form-control {errors.valor ? 'is-invalid' : ''}" bind:value={form.valor}>

        {#if errors.valor}
            <span class="invalid-feedback" role="alert">
                <strong>{errors.valor[0]}</strong>
            </span>
        {/if}
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="forma" class="form-label">Forma de pago</label>
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
    <div class="form-group mb-3 col-12 col-md-6">
        <label for="observacion" class="form-label">Observaciones</label>
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

    <div>
        <button class="btn btn-primary">Enviar</button>
        <button type="button" class="btn btn-danger">Cancelar</button>
    </div>
</form>