<?php
require_once "./conexion_mongodb/conexion.php";
require_once "./conexion_mongodb/crud.php";
class module_format
{
    public function format($metrica, $place)
    {
        $con = new crud();
        $title = ucfirst($metrica);
        $coleccion="datos_".strtolower($place);
        $datos = $con->mostrarultimodato($coleccion);
        if ($metrica == "temperature") {
            if ($datos->temperature > 24) {
                $advertencia = "Look for a shelter there is excess heat 🥵";
            } elseif ($datos->temperature >= 18) {
                $advertencia = "Normal temperature 😁";
            } elseif ($datos->temperature < 18) {
                $advertencia = "Look for a coat low temperatures 🥶";
            }
        } elseif ($metrica == "humidity") {
            if ($datos->humidity > 60) {
                $advertencia = "Very humid air use a dehumidifier 💧";
            } elseif ($datos->humidity >= 30) {
                $advertencia = "Comfortable humidity 🌈";
            } elseif ($datos->humidity < 30) {
                $advertencia = "Very dry air use a humidifier 🔥";
            }
        } elseif ($metrica == "UV") {
            if ($datos->UV <= 2) {
                $advertencia = "Low UV does not need protection 😁";
            } elseif ($datos->UV <= 5) {
                $advertencia = "Moderate UV needs protection 😥";
            } elseif ($datos->UV <= 7) {
                $advertencia = "High UV needs protection 🥵";
            } elseif ($datos->UV <= 10) {
                $advertencia = "Very high UV needs extra protection 🔥";
            } elseif ($datos->UV >= 11) {
                $advertencia = "Extreme UV needs extra protection 🔥🔥";
            }
        }
        echo "<form class='form-module' name='$metrica$place' method='POST' action='smart-lab?module=graficas.php'>";
        echo "<a onclick=enviarFormulario('$metrica$place')>";
        echo "<input type='text' name='coleccion' value='$coleccion' hidden>";
        echo "<input type='text' name='tipo_dato' value='$metrica' hidden>";
        echo "<input type='text' name='place' value='$place' hidden>";
        echo "<div class='modulebackm'></div>";
        echo "<div class='content'>";
        echo "<h2>$title</h2>";
        echo "<h3>$place</h3>";
        echo "<h3><u>$datos->date</u></h3>";
        if ($metrica == "temperature") {
            echo "<p>$datos[$metrica]&deg;C</p>";
        } elseif ($metrica == "humidity") {
            echo "<p>$datos[$metrica]%</p>";
        }
        elseif ($metrica == "UV") {
            echo "<p>$datos[$metrica]</p>";
        }
        echo "<h4>$datos->time <br></h4>";
        echo "<h3>$advertencia</h3>"; 
        echo "</div>";
        echo "</a>";
        echo "</form>";

    }
}

?>