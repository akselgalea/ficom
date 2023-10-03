<script>
	import { toCLP } from "../../../../helpers/helpers";
	import DeletePagoForm from "./DeletePagoForm.svelte";
	import EditPagoForm from "./EditPagoForm.svelte";
    export let estudiante, pagos;

    const emptyRow = `
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    `;

    const onUpdated = (event) => {
        const {oldPago, newPago} = event.detail;

        pagos[oldPago.mes] = pagos[oldPago.mes].filter(pago => pago.id !== oldPago.id);
        pagos[newPago.mes] = pagos[newPago.mes].concat(newPago);
    }

    const onDeleted = (event) => {
        const {pagoId, mes} = event.detail;

        pagos[mes] = pagos[mes].filter(pago => pago.id !== pagoId);
    }
</script>
<div class="card p-4 mt-3">
    <h2 class="mb-3">Pagos</h2>
    <div class="tabla-pagos-container">
        <table class="table table-bordered border-dark">
            <tbody>
                <tr class="datos-estudiante text-center [&>td]:t-border-2">
                    <td colspan="2" style="width: 45%">{ estudiante.apellidos }</td>
                    <td style="width: 30%">{ estudiante.nombres }</td>
                    <td style="width: 130px">{ `${estudiante.rut} - ${estudiante.dv}` }</td>
                    <td style="width: 60px">{ `${estudiante.curso.curso} - ${estudiante.curso.paralelo}` }</td>
                    <td rowspan="2" style="width: 5ch">{ new Date().getFullYear() }</td>
                </tr>
                <tr class="[&>td]:t-border-2">
                    <td style="width: 10ch">Alumno:</td>
                    <td class="text-center">Apellido paterno y materno</td>
                    <td class="text-center">Nombres</td>
                    <td class="text-center">RUN</td>
                    <td class="text-center">NIVEL</td>
                </tr>
                <tr class="[&>td]:t-border-2">
                    <td colspan="6">Nombre Apoderado: { estudiante.apoderados.apoderado_titular.nombre ?? '' }</td>
                </tr>
                <tr class="[&>td]:t-border-2">
                    <td colspan="2">Teléfono: { estudiante.apoderados.apoderado_titular.telefono ?? '' }</td>
                    <td colspan="4">Correo Electrónico: { estudiante.apoderados.apoderado_titular.email ?? '' }</td>
                </tr>
            </tbody>
        </table>

        <div class="t-w-full {window.width < 820 ? 't-overflow-x-scroll' : '' }">
            <header class="t-grid t-grid-cols-8 t-text-center t-border t-border-black [&>div]:t-border [&>div]:t-border-black [&>div]:t-h-full [&>div]:t-py-2 last:t-border-0">
                <div>Meses</div>
                <div>Documento</div>
                <div>N° Documento</div>
                <div>Fecha</div>
                <div>Valor</div>
                <div>Forma de pago</div>
                <div>Observaciones</div>
                <div>Opciones</div>
            </header>
            <div class="t-flex t-flex-col t-text-center">
                {#each Object.keys(pagos) as mes}
                    {#if pagos[mes].length > 1}
                        <div class="t-grid t-grid-cols-8 t-border t-border-black t-items-center t-grid-rows-{pagos[mes].length}">
                            <div class="text-capitalize t-border t-border-black t-h-full t-flex t-items-center t-justify-center t-col-span-1 t-rows-span-{pagos[mes].length}">{mes === 'matricula' ? 'matrícula' : mes}</div>
                            <div class="
                                t-col-span-7
                                t-row-span-{pagos[mes].length}
                                t-grid t-grid-cols-7
                                t-items-center
                                [&>div]:t-border
                                [&>div]:t-border-black
                                [&>div]:t-h-full
                                [&>div]:t-flex
                                [&>div]:t-items-center
                                [&>div]:t-justify-center
                                [&>div]:t-py-2
                            "
                            >
                            {#each pagos[mes] as pago (pago.id)}
                                <div class="text-uppercase">{ pago.documento }</div>
                                <div>{ pago.num_documento }</div>
                                <div>{ pago.fecha_pago }</div>
                                <div>{ toCLP(pago.valor) }</div>
                                <div class="text-capitalize">{ pago.forma }</div>
                                <div>{ pago.observacion ?? '' }</div>
                                <div class="t-gap-2">
                                    <EditPagoForm {pago} on:updated={onUpdated} />
                                    <DeletePagoForm pago={pago} on:deleted={onDeleted} />
                                </div>
                            {/each}
                            </div>
                        </div>
                    {:else if pagos[mes].length === 1}
                        <div class="t-grid t-grid-cols-8 t-border t-border-black t-items-center [&>div]:t-border [&>div]:t-border-black [&>div]:t-flex [&>div]:t-items-center [&>div]:t-justify-center [&>div]:t-h-full [&>div]:t-py-2">
                            <div class="text-capitalize">{mes === 'matricula' ? 'matrícula' : mes}</div>
                            <div class="text-uppercase">{pagos[mes][0].documento}</div>
                            <div>{pagos[mes][0].num_documento}</div>
                            <div>{pagos[mes][0].fecha_pago}</div>
                            <div>{toCLP(pagos[mes][0].valor)}</div>
                            <div class="text-capitalize">{pagos[mes][0].forma}</div>
                            <div>{pagos[mes][0].observacion ?? ''}</div>
                            <div class="t-gap-2">
                                <EditPagoForm pago={pagos[mes][0]} on:updated={onUpdated} />
                                <DeletePagoForm pago={pagos[mes][0]} on:deleted={onDeleted} />
                            </div>
                        </div>
                    {:else}
                        <div class="t-grid t-grid-cols-8 t-border t-border-black t-items-center [&>div]:t-border [&>div]:t-border-black [&>div]:t-h-full [&>div]:t-py-2">
                            <div class="text-capitalize">{mes === 'matricula' ? 'matrícula' : mes}</div>
                            {@html emptyRow}
                        </div>
                    {/if}
                {/each}
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        max-width: 1080px;
        margin: 0 auto;
    }
</style>