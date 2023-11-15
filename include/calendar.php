<?php
// Obtener el mes y el año actual
$mes_actual = date('m');
$año_actual = date('Y');

// Obtener el mes y el año de la URL si están presentes
if (isset($_GET['mes']) && isset($_GET['año'])) {
    $mes_actual = $_GET['mes'];
    $año_actual = $_GET['año'];
}

// Obtener el primer y último día del mes
$primer_dia = mktime(0, 0, 0, $mes_actual, 1, $año_actual);
$ultimo_dia = mktime(0, 0, 0, $mes_actual + 1, 0, $año_actual);

// Obtener el número de días en el mes actual
$num_dias = date("t", $primer_dia);

// Obtener el nombre del mes y el año
$nombre_mes = date("F", $primer_dia);
$nombre_mes = date("F", strtotime($nombre_mes));
$nombre_año = date("Y", $primer_dia);

// Obtener el día de la semana del primer día del mes
$dia_semana = date("N", $primer_dia);

// Calcular el número de filas necesarias en el calendario
$num_filas = ceil(($num_dias + $dia_semana - 1) / 7);

// Nombres de los meses en inglés
$meses = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
);

// Nombres de los días en inglés
$dias = array(
    'Mon',
    'Tue',
    'Wed',
    'Thu',
    'Fri',
    'Sat',
    'Sun'
);
?>

<!DOCTYPE html>
<html>

<body>
    <div class="calendar">
        <div class="month">
            <span class="prev" onclick="cambiarMes(-1)">&#10094;</span>
            <?php echo $meses[$mes_actual - 1] . ' ' . $nombre_año; ?>
            <span class="next" onclick="cambiarMes(1)">&#10095;</span>
        </div>
        <table>
            <tr>
                <?php foreach ($dias as $dia) { ?>
                    <th>
                        <?php echo $dia; ?>
                    </th>
                <?php } ?>
            </tr>
            <?php
            $numero_dia = 1;
            for ($i = 0; $i < $num_filas; $i++) {
                echo "<tr>";
                for ($j = 1; $j <= 7; $j++) {
                    if (($i == 0 && $j < $dia_semana) || ($numero_dia > $num_dias)) {
                        echo "<td></td>";
                    } else {
                        $class = '';
                        if ($numero_dia == date('j') && $mes_actual == date('n') && $año_actual == date('Y')) {
                            // No se aplica la clase "selected" al día actual
                        } else {
                            $class = 'class="clickable"';
                        }
                        echo "<td $class onclick='seleccionarDia($numero_dia, this)'>$numero_dia</td>";
                        $numero_dia++;
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <script>
        var diasSeleccionados = [];
        var mesActual = <?php echo $mes_actual; ?>;
        var añoActual = <?php echo $año_actual; ?>;

        function cambiarMes(delta) {
            mesActual += delta;
            if (mesActual < 1) {
                mesActual = 12;
                añoActual -= 1;
            } else if (mesActual > 12) {
                mesActual = 1;
                añoActual += 1;
            }
            mostrarCalendario(mesActual, añoActual);
        }

        function mostrarCalendario(mes, año) {
            // Obtener el primer y último día del mes
            var primerDia = new Date(año, mes - 1, 1);
            var ultimoDia = new Date(año, mes, 0);

            // Obtener el número de días en el mes actual
            var numDias = ultimoDia.getDate();

            // Obtener el nombre del mes y el año
            var nombreMes = primerDia.toLocaleString('en-US', { month: 'long' });
            var nombreAño = primerDia.getFullYear();

            // Obtener el día de la semana del primer día del mes
            var diaSemana = primerDia.getDay();

            // Calcular el número de filas necesarias en el calendario
            var numFilas = Math.ceil((numDias + diaSemana) / 7);

            // Actualizar el encabezado del mes y el año
            document.querySelector('.month').innerHTML = `
                <span class="prev" onclick="cambiarMes(-1)">&#10094;</span>
                ${nombreMes} ${nombreAño}
                <span class="next" onclick="cambiarMes(1)">&#10095;</span>
            `;

            // Generar el calendario actualizado
            var calendarTable = document.querySelector('table');
            calendarTable.innerHTML = '<tr>' +
                '<th>Sun</th>' +
                '<th>Mon</th>' +
                '<th>Tue</th>' +
                '<th>Wed</th>' +
                '<th>Thu</th>' +
                '<th>Fri</th>' +
                '<th>Sat</th>' +
                '</tr>';

            var numeroDia = 1;
            for (var i = 0; i < numFilas; i++) {
                var row = document.createElement('tr');
                for (var j = 0; j < 7; j++) {
                    if ((i === 0 && j < diaSemana) || numeroDia > numDias) {
                        row.innerHTML += '<td></td>';
                    } else {
                        var dateStr = numeroDia.toString().padStart(2, '0') + '-' + mes.toString().padStart(2, '0') + '-' + año;
                        var classStr = numeroDia === new Date().getDate() && mes === (new Date().getMonth() + 1) && año === new Date().getFullYear() ? '' : 'class="clickable"';
                        row.innerHTML += `<td ${classStr} onclick="seleccionarDia('${dateStr}', this)">${numeroDia}</td>`;
                        numeroDia++;
                    }
                }
                calendarTable.appendChild(row);
            }
        }

        function seleccionarDia(fecha, elemento) {
            elemento.classList.toggle('selected');

            if (elemento.classList.contains('selected')) {
                diasSeleccionados.push(fecha);
            } else {
                var index = diasSeleccionados.indexOf(fecha);
                if (index > -1) {
                    diasSeleccionados.splice(index, 1);
                }
            }

            graficar(diasSeleccionados, '<?php echo $coleccion ?>', '<?php echo $metrica ?>');
        }

        // Mostrar el calendario inicial al cargar la página
        mostrarCalendario(mesActual, añoActual);
    </script>
    <button onclick="guardararchivoCSV(diasSeleccionados,'<?php echo $coleccion ?>','<?php echo $metrica ?>')"
        class="export-csv">Export
        CSV</button>
</body>

</html>