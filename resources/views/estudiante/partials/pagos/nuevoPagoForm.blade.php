<form method="POST" action="{{route('pago.store', $estudiante->id)}}" id="formPago" class="mt-3 row">
    @csrf
    <h2>Registrar pago</h2>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="mes" class="form-label">Mes</label>
        <select name="mes" class="form-control form-select @error('mes') is-invalid @enderror">
            <option value="" selected disabled hidden>Selecciona una opción</option>
            <option value="matricula" @selected($mesAPagar == 'matricula')>Matrícula</option>
            <option value="marzo" @selected($mesAPagar == 'marzo')>Marzo</option>
            <option value="abril" @selected($mesAPagar == 'abril')>Abril</option>
            <option value="mayo" @selected($mesAPagar == 'mayo')>Mayo</option>
            <option value="junio" @selected($mesAPagar == 'junio')>Junio</option>
            <option value="julio" @selected($mesAPagar == 'julio')>Julio</option>
            <option value="agosto" @selected($mesAPagar == 'agosto')>Agosto</option>
            <option value="septiembre" @selected($mesAPagar == 'septiembre')>Septiembre</option>
            <option value="octubre" @selected($mesAPagar == 'octubre')>Octubre</option>
            <option value="noviembre" @selected($mesAPagar == 'noviembre')>Noviembre</option>
            <option value="diciembre" @selected($mesAPagar == 'diciembre')>Diciembre</option>
        </select>

        @error('mes')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="anio" class="form-label">Año</label>
        <select name="anio" class="form-control form-select @error('anio') is-invalid @enderror">
            <option value="" selected disabled hidden>Selecciona una opción</option>
            <option value="2022" @selected(old('anio') == '2022' || now()->year == '2022')>2022</option>
            <option value="2023" @selected(old('anio') == '2023' || now()->year == '2023')>2023</option>
            <option value="2024" @selected(old('anio') == '2024' || now()->year == '2024')>2024</option>
        </select>

        @error('anio')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="documento" class="form-label">Documento</label>
        <select name="documento" class="form-control form-select @error('documento') is-invalid @enderror">
            <option value="" selected disabled hidden>Selecciona una opción</option>
            <option value="boleta"  @selected(old('documento') == 'boleta')>Boleta</option>
            <option value="recibo"  @selected(old('documento') == 'recibo')>Recibo</option>
            <option value="vale vista"  @selected(old('documento') == 'vale vista')>Vale vista</option>
        </select>

        @error('documento')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="num_documento" class="form-label">N° Documento</label>
        <input type="number" name="num_documento" class="form-control @error('num_documento') is-invalid @enderror" value="{{ old('num_documento') }}">

        @error('num_documento')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="fecha_pago" class="form-label">Fecha</label>
        <input type="date" name="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror" value={{ old('fecha_pago') ? old('fecha_pago') : today()}}>

        @error('fecha_pago')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="valor" class="form-label">Valor</label>
        <input type="number" name="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor') }}">

        @error('valor')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-6 col-md-4">
        <label for="forma" class="form-label">Forma de pago</label>
        <select name="forma" class="form-control form-select @error('forma') is-invalid @enderror">
            <option value="" selected disabled hidden>Selecciona una opción</option>
            <option value="efectivo" @selected(old('forma') == 'efectivo')>Efectivo</option>
            <option value="cheque" @selected(old('forma') == 'cheque')>Cheque</option>
            <option value="transferencia" @selected(old('forma') == 'transferencia')>Transferencia</option>
        </select>

        @error('forma')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group mb-3 col-12 col-md-6">
        <label for="observacion" class="form-label">Observaciones</label>
        <textarea name="observacion" class="form-control @error('observacion') is-invalid @enderror">{{ old('observacion') }}</textarea>

        @error('observacion')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <input type="hidden" name="total" value="{{$total}}" />
    <div>
        <button class="btn btn-primary">Enviar</button>
        <button type="button" class="btn btn-danger" onclick="cancelForm()">Cancelar</button>
    </div>
</form>


@push('scripts')
<script>
    function cancelForm() {
        const form = document.getElementById('formPago');
        form.reset();
    }
</script>
@endpush