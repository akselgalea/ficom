# ficom

## todo

### Ficha estudiante:
1. Imprimir el reporte de Excel en una hoja. -- Al generar un alumno nuevo, que se imprima en una sola hoja, sin tener que configurar nada
2. Verificar la integridad de los campos de la ficha de estudiantes, especialmente asegurándose de que se carguen los datos de los apoderados. --falta el script
3. Hacer que el ingreso de información en el campo "Suplente 1" sea obligatorio, mientras que los demás campos suplentes deben ser opcionales. -- Suplente para retiro

### Historial de pago:
-- Voy a pasar esto a Svelte (JS), porque quieren que sea dinámico.
-- Al generar un pago, el recibo es cheque o vale vista
-- Al generar un pago, la forma de pago de la boleta es efectivo o transferencia
-- Cambiar la posicion de los campos forma de pago y tipo de documento (swap)
1. Agregar campo de "Vale Vista" a los tipos de documento. --FORMAS DE PAGO -- DONE
2. Revisar el flujo de cambio de años en el historial de pagos, manteniendo un registro histórico.
-- Al cambiar año que se carguen los pagos de años pasados
-- Generar tabla pivote con el arancel y el año correspondiente
3. Habilitar la edición de los campos pertinentes. --DONE
-- Editar la tabla de pagos, poder modificar los pagos -- DONE
4. Hacer que el campo "Observación" sea opcional en esta sección. --DONE

### Listado de Estudiantes:
1. Corregir reporte FICOM todos los estudiantes, permitiendo la agrupación por curso. -- Agrupar por cursos en el excel? O permitir descargar el reporte ficom de un curso?
-- Separar por hoja o agregar la columna curso
2. En el registro escolar, eliminar el porcentaje "%" del RUT que aparece por error al descargar excel. --DONE
3. Utilizar un código de colores en el listado de estudiantes: 
-Azul para los estudiantes prioritarios que se mantienen.
-Rojo para los nuevos estudiantes prioritarios.
-Blanco para los alumnos regulares de y aquellos que tienen algún porcentaje de beca (incluyendo a los que tienen beca del 100%); que dejan de ser prioritarios. -- DONE
-- crear tabla que almacene el estado del alumno en X año (prioridad, curso, año, estudiante_id)

4. Al buscar usando la paginación, mantener los parámetros de búsqueda al cambiar de página.

### Finanzas:
1. Agregar un nivel adicional de categorización, dividiendo los niveles en prebásica, básica, media y cuarto medio. -- DONE
-- Crear seeder de el año y el nivel (aranceles)
2. Generar un reporte por cursos que sume las cantidades correspondientes a boletas y recibos por separado, incorporando un campo para el total de boletas y otro para el total de recibos.
-- 

-- Preguntar por el campo abono

3. Incluir las siguientes columnas en el reporte:
   - Total Boleta
   - Total Recibo
   - Total Pendiente solo con boleta --deuda hasta el mes actual
   - Total meses de deuda por cada niño -- hasta la fecha (si está al día - 0 (solo considerar boleta))
-- NO MODIFICAR LOS CAMPOS, SOLO AGREGAR ESTOS CAMPOS