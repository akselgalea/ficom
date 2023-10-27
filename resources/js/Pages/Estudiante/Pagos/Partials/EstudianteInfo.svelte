<script>
    import { toCLP } from '../../../../helpers/helpers.js'

    export let estudiante, mensualidad, periodoAnterior;
</script>

<div class="row">
    <div class="buttons mb-3">
        {#if !periodoAnterior}
        <a href={`/estudiantes/${estudiante.id}/generar-reporte`} class="btn btn-primary">Generar reporte FICOM</a>
        {:else}
        <a href={`/estudiantes/${estudiante.id}/generar-reporte?periodo=${periodoAnterior.periodo}`} class="btn btn-primary">Generar reporte FICOM</a>
        {/if}
    </div>

    <h2>Información del estudiante{periodoAnterior ? ` - periodo ${periodoAnterior.periodo}` : ''}</h2>
    <div class="form-group mb-3 col-4">
        <span class="form-span">Estudiante</span>
        <p class="form-control">{ `${estudiante.nombres} ${estudiante.apellidos}` }</p>
    </div>
    
    <div class="form-group mb-3 col-4">
        <span class="form-span">Curso</span>
        {#if !periodoAnterior}
        <p class="form-control">{ `${estudiante.curso.curso} - ${estudiante.curso.paralelo}` }</p>
        {:else}
        <p class="form-control">{ `${periodoAnterior.curso.curso} - ${periodoAnterior.curso.paralelo}` }</p>
        {/if}
    </div>
    
    <div class="form-group mb-3 col-4">
        <span class="form-span">Prioridad</span>
        {#if !periodoAnterior}
        <p class="form-control flc">{ estudiante.periodo_actual.prioridad }</p>
        {:else}
        <p class="form-control flc">{ periodoAnterior.prioridad }</p>
        {/if}
    </div>

    <div class="form-group mb-3 col-4">
        <span class="form-span">Monto mensualidad</span>
        <p class="form-control">{ toCLP(estudiante.curso?.nivel?.periodo_actual.mensualidad) }</p>
    </div>
    <div class="form-group mb-3 col-4">
        <span class="form-span">% Beca</span>
        {#if !periodoAnterior}
        <p class="form-control flc">{ estudiante.beca ? estudiante.beca.descuento : 'No tiene beca' }</p>
        {:else}
        <p class="form-control flc">{ periodoAnterior.beca ? periodoAnterior.beca.descuento : 'No tiene beca' }</p>
        {/if}
    </div>
    <div class="form-group mb-3 col-4">
        <span class="form-span">Total a pagar</span>
        <p class="form-control">{ toCLP(mensualidad) }</p>
    </div>
</div>