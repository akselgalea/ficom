@php
  function retirado($deleted, $index) {
    if($deleted) {
      return 'X';
    }

    return $index + 1;
  }
@endphp

<table>
  <thead>
      <tr>
          <th>N°</th>
          <th>APELLIDOS</th>
          <th>NOMBRES</th>
          <th>RUT</th>
          <th>RUT SIN GIÓN NI DIGITO VERIFICADOR (NO RELLENAR COLUMNA, SE HACE CON FORMULA)</th>
          <th>CLAVE INGRESO G SUITE</th>
          <th>ALUMNO NUEVO</th>
          <th>CORREO INSTITUCIONAL ALUMNO(A)</th>
          <th>NOMBRES APOD.</th>
          <th>TELÉFONO APOD.</th>
          <th>CORREO APOD.</th>
          <th>DIRECCIÓN</th>
      </tr>
  </thead>
  <tbody>
      @foreach($estudiantes as $index => $es)
          <tr>
              <td class="font-weight-bold">{{ retirado($es->deleted_at, $index) }}</td>
              <td>{{ $es->apellidos }}</td>
              <td>{{ $es->nombres }}</td>
              <td>{{ $es->rut . '-' . $es->dv }}%</td>
              <td>{{ $es->rut }}</td>
              <td>{{ $es->rut . 'A' }}</td>
              <td>{{ $es->es_nuevo ? 'X' : '' }}</td>
              <td>{{ $es->correo_institucional ?? '' }}</td>
              <td>{{ $es->apoderados['apoderado_titular']['nombre'] ?? '' }}</td>
              <td>{{ $es->apoderados['apoderado_titular']['telefono'] ?? '' }}</td>
              <td>{{ $es->apoderados['apoderado_titular']['email'] ?? '' }}</td>
              <td>{{ $es->direccion ?? '' }}</td>
          </tr>
      @endforeach
  </tbody>
</table>