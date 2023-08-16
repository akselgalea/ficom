<table>
  <thead>
      <tr>
          <th>N°</th>
          <th>NOMBRE COMPLETO</th>
          <th>Monto anual</th>
          <th>% Beca</th>
          <th>Excención</th>
          <th>Prioritario</th>
          <th>MATRÍCULA</th>
          <th>ABONO</th>
          <th>MONTO CANCELADO</th>
          <th>MONTO PENDIENTE A PAGO</th>
      </tr>
  </thead>
  <tbody>
      @foreach($registros as $index => $registro)
          <tr>
              <td class="font-weight-bold">{{$index + 1}}</td>
              <td>{{ $registro['nombre_completo'] }}</td>
              <td>{{ toCLP($registro['monto_anual']) }}</td>
              <td>{{ $registro['beca'] }}%</td>
              <td>{{ $registro['excencion'] }}</td>
              <td>{{ $registro['prioritario'] }}</td>
              <td>{{ toCLP($registro['matricula']) }}</td>
              <td>{{ toCLP($registro['abono']) }}</td>
              <td>{{ toCLP($registro['monto_cancelado']) }}</td>
              <td>{{ toCLP($registro['monto_pendiente']) }}</td>
          </tr>
      @endforeach
  </tbody>
</table>