<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <section class="mail">
        <header>
            <h1>Hola {{$apoderado->nombres}}!</h1>
            <h3>Te enviamos este correo para informarte que todavía no se ha hecho el pago total del mes de {{$datosPago['mes']}} del alumno {{"$estudiante->nombres $estudiante->apellidos"}}</h3>
        </header>
        <div class="mail-body">
            <p>Monto arancel: {{$datosPago['arancel']}}</p>
            <p>Descuentos: {{$datosPago['totalDescuentos']}}%</p>
            <p>Monto abonado: {{$datosPago['abonado']}}</p>
            <p>Total a pagar: {{$datosPago['totalPagar']}}</p>
        </div>
    </section>
</body>
</html>