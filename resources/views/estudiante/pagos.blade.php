@extends('layouts.app')
@section('content')
@php
    $totalAPagarPorMes = $estudiante->getTotalAPagarPorMes();
    $mesAPagar = old('mes') ?? $estudiante->mesFaltante($estudiante->pagos_anio, $totalAPagarPorMes);
    $total = $estudiante->getTotalAPagarMes($mesAPagar);
@endphp

<div class="container card">
    @include('estudiante.partials.pagos.informacion')
    @include('estudiante.partials.pagos.nuevoPagoForm')
</div>

@include('estudiante.partials.pagos.pagos')
@endsection
