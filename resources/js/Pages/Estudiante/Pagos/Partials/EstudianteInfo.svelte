<script>
    import { toCLP } from '../../../../helpers/helpers.js'

    export let estudiante, mensualidad;
</script>

<div class="row">
    <div class="buttons mb-3">
        <a href={`/estudiantes/${estudiante.id}/generar-reporte`} class="btn btn-primary">Generar reporte FICOM</a>
    </div>

    <h2>Información del estudiante</h2>
    <div class="form-group mb-3 col-4">
        <span class="form-span">Estudiante</span>
        <p class="form-control">{ `${estudiante.nombres} ${estudiante.apellidos}` }</p>
    </div>
    
    <div class="form-group mb-3 col-4">
        <span class="form-span">Curso</span>
        <p class="form-control">{ `${estudiante.curso.curso} - ${estudiante.curso.paralelo}` }</p>
    </div>
    
    <div class="form-group mb-3 col-4">
        <span class="form-span">Prioridad</span>
        <p class="form-control flc">{ estudiante.prioridad }</p>
    </div>

    <div class="form-group mb-3 col-4">
        <span class="form-span">Monto mensualidad</span>
        <p class="form-control">{ toCLP(estudiante.curso?.nivel?.periodo_actual.mensualidad) }</p>
    </div>
    <div class="form-group mb-3 col-4">
        <span class="form-span">% Beca</span>
        <p class="form-control">{ estudiante.beca ? estudiante.beca.descuento : 'No tiene beca' }</p>
    </div>
    <div class="form-group mb-3 col-4">
        <span class="form-span">Total a pagar</span>
        <p class="form-control">{ toCLP(mensualidad) }</p>
    </div>
</div>