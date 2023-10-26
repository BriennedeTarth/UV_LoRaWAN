<?php
require_once "crud.php";
if (isset($_POST['datos'])) {
    $coleccion = $_GET['coleccion'];
    $tipo_dato= $_GET['tipo_dato'];
    $fechas = json_decode($_POST['datos'], true);
    $resultados = [];
    foreach ($fechas as $fecha) {
        $resultados[$fecha] = [
            $tipo_dato => [],
            'time'=>[]
        ];
    }
    $con = new Crud();
    $datos = $con->mostrardatos($coleccion, $fechas);

    foreach ($datos as $document) {
        $fecha = $document->date;
        $resultados[$fecha][$tipo_dato][] = $document[$tipo_dato];
        $resultados[$fecha]['time'][] = $document->time;
    }
    echo json_encode($resultados);

} else {
    echo "Algo salió mal";
}
?>