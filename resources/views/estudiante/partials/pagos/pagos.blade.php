<div class="container card mt-3">
    <h2 class="mb-3">Pagos</h2>
    <div class="tabla-pagos-container">
        <table class="tabla-pagos table table-bordered border-dark">
            <tbody>
                <tr class="datos-estudiante text-center">
                    <td colspan="2" style="width: 45%">{{ $estudiante->apellidos }}</td>
                    <td style="width: 30%">{{ $estudiante->nombres }}</td>
                    <td style="width: 130px">{{ $estudiante->rut . '-' . $estudiante->dv }}</td>
                    <td style="width: 60px">{{ $estudiante->curso->curso . '-' . $estudiante->curso->paralelo }}</td>
                    <td rowspan="2" style="width: 5ch">{{ now()->year }}</td>
                </tr>
                <tr>
                    <td style="width: 10ch">Alumno:</td>
                    <td class="text-center">Apellido paterno y materno</td>
                    <td class="text-center">Nombres</td>
                    <td class="text-center">RUN</td>
                    <td class="text-center">NIVEL</td>
                </tr>
                <tr>
                    <td colspan="6">Nombre Apoderado: {{ $estudiante->apoderados['apoderado_titular']['nombre'] ?? '' }}</td>
                </tr>
                <tr>
                    <td colspan="2">Teléfono: {{ $estudiante->apoderados['apoderado_titular']['telefono'] ?? '' }}</td>
                    <td colspan="4">Correo Electrónico: {{ $estudiante->apoderados['apoderado_titular']['email'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="tabla-pagos table table-bordered border-dark">
            <thead>
              <tr>
                <th scope="col" style="width: 123px">Meses</th>
                <th scope="col" style="width: 123px">Documento</th>
                <th scope="col" style="width: 150px">N° Documento</th>
                <th scope="col" style="width: 120px">Fecha</th>
                <th scope="col" style="width: 130px">Valor</th>
                <th scope="col" style="width: 150px">Forma de pago</th>
                <th scope="col" style="width: auto">Observaciones</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Matrícula</td>
                    @if(count($estudiante->pagos_anio['matricula']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['matricula'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['matricula']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['matricula'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Marzo</td>
                    @if(count($estudiante->pagos_anio['marzo']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['marzo'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['marzo']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['marzo'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Abril</td>
                    @if(count($estudiante->pagos_anio['abril']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['abril'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['abril']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['abril'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Mayo</td>
                    @if(count($estudiante->pagos_anio['mayo']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['mayo'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['mayo']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['mayo'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Junio</td>
                    @if(count($estudiante->pagos_anio['junio']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['junio'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['junio']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['junio'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Julio</td>
                    @if(count($estudiante->pagos_anio['julio']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['julio'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['julio']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['julio'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Agosto</td>
                    @if(count($estudiante->pagos_anio['agosto']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['agosto'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['agosto']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['agosto'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Septiembre</td>
                    @if(count($estudiante->pagos_anio['septiembre']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['septiembre'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['septiembre']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['septiembre'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Octubre</td>
                    @if(count($estudiante->pagos_anio['octubre']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['octubre'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['octubre']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['octubre'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Noviembre</td>
                    @if(count($estudiante->pagos_anio['noviembre']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['noviembre'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['noviembre']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['noviembre'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
                <tr>
                    <td>Diciembre</td>
                    @if(count($estudiante->pagos_anio['diciembre']) > 1)
                        <td class="multiples-pagos" colspan="6">
                            @foreach($estudiante->pagos_anio['diciembre'] as $pago)
                                <div class="detalles">
                                    <div class="text-uppercase" style="width: 122px">{{ $pago['documento'] }}</div>
                                    <div style="width: 150px">{{ $pago['num_documento'] }}</div>
                                    <div style="width: 120px">{{ $pago['fecha_pago'] }}</div>
                                    <div style="width: 130px">{{ toCLP($pago['valor']) }}</div>
                                    <div class="text-capitalize" style="width: 150px">{{ $pago['forma'] }}</div>
                                    <div style="width: auto">{{ $pago['observacion'] }}</div>
                                </div>
                            @endforeach
                        </td>
                    @elseif(count($estudiante->pagos_anio['diciembre']) == 0)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        @foreach ($estudiante->pagos_anio['diciembre'] as $pago)    
                            <td class="text-center text-uppercase">{{$pago['documento']}}</td>
                            <td class="text-center">{{$pago['num_documento']}}</td>
                            <td class="text-center">{{$pago['fecha_pago']}}</td>
                            <td class="text-center">{{toCLP($pago['valor'])}}</td>
                            <td class="text-center text-capitalize">{{$pago['forma']}}</td>
                            <td>{{$pago['observacion']}}</td>
                        @endforeach
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>