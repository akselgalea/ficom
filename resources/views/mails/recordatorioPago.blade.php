<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <style>
        .mail {
            padding: 1.5rem;
            border: 1px solid black;
            border-radius: 1rem;
            max-width: 500px;
            margin: auto auto;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .mail-body {
            display: grid;
            gap: 1rem;
        }
        
        .mail-header h1 {
            text-align: center;
        }
        
        .pricelist {
            border: 1px solid black;
            border-radius: 5px;
            font-weight: 600;
        }

        .pricelist div {
            padding: .35rem 1rem;
        }

        .pricelist div:not(:last-child) {
            border-bottom: 1px solid black;
        }

        .total {
            margin-top: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }
        </style>
</head>

<body>
    <div class="mail">
        <div class="mail-header">
            <h1>Hola {{$apoderado->nombres}}!</h1>
            <h3>Te enviamos este correo para informarte que todavía no se ha hecho el pago total del mes de {{$datosPago['mes']}} del alumno {{"$estudiante->nombres $estudiante->apellidos"}}</h3>
        </div>

        <div class="mail-body">
            <div class="pricelist">
                <div>Monto arancel: {{toCLP($datosPago['arancel'])}}</div>
                <div>Descuentos: {{$datosPago['totalDescuentos']}}%</div>
                <div>Monto abonado: {{toCLP($datosPago['abonado'])}}</div>
            </div>
            <p class="total">Total a pagar: {{toCLP($datosPago['totalPagar'])}}</p>
        </div>
    </div>
</body>
</html>