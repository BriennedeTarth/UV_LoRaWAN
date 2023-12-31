<?php
//Archivos de conexion de mongodb
include "../conexion_mongodb/conexion.php";
include "../conexion_mongodb/crud.php";

set_time_limit(0);
function conectar()
{
  require_once("phpMQTT.php");

  $mqttServer = "192.168.0.2"; // Dirección IP o nombre de dominio de tu servidor Mosquitto
  $mqttPort = 8883; // Puerto del complemento WebSocket de Mosquitto
  $mqttUsername = "vicky"; // Nombre de usuario MQTT (si es necesario)
  $mqttPassword = "1234"; // Contraseña MQTT (si es necesario)
  $mqttClientId = "cliente_phpbrandon"; // ID del cliente MQTT (debe ser único para cada cliente)
  $mqttCafile = 'C:\Program Files\mosquitto\ca.crt';
  $topic = 'demo';

  $mqtt = new phpMQTT($mqttServer, $mqttPort, $mqttClientId, $mqttCafile);



  if ($mqtt->connect(true, NULL, $mqttUsername, $mqttPassword)) {
    $con = new crud();

    $mensaje = $mqtt->subscribeAndWaitForMessage('demo', 0);
    $mqtt->close();
    $datos = separarmensaje($mensaje);
    if ($datos["UV"] >= 0) {
      //echo "Tipo antes de guardar ".gettype($datos["UV"])."\n";
      $respuesta = $con->insertardatos($datos);
      if ($respuesta->getInsertedCount() > 0) {
        echo "Datos correctamente almacenados:" . $datos["UV"] . "\n";
      } else {
        echo "error";
      }
    }

    

    flush();
  }
}

function separarmensaje($mensaje)
{
  //{ "device_id": "4e-4f-4d-42-52-45-31-32", "sensor": "UV Level=0\u0000" , "tiempo": "2023-06-23T19:29:42.152605Z" }
  $data = json_decode($mensaje);
  //Crear variable aux
  $payload = $data->sensor;
  $uv = trim($payload, "UV Level=");
  $UVlevel = explode("\0", $uv);
  try {
    if (count($UVlevel)>1) {
      $UVlevel[0] = (int) $UVlevel[0];
    } 
  } catch (TypeError $e) {
    $UVlevel[0] = -1256;
  }
  //Crear una variable auxiliar
  $date_time =  $data->tiempo;
  //De la primera parte separla donde existe una T-------------------------------------------------------------
  $date_time_s = explode("T", $date_time);
  //Guardar fecha y hora con fomatos adecuados
  $date = date("d-m-Y", strtotime($date_time_s[0]));
  $time = date("H:i", strtotime($date_time_s[1]));

  $datos_separados = array(
    "device_id" => $data->device_id,
    "UV" => $UVlevel[0],
    "date" => $date,
    "time" => $time
  );
  return $datos_separados;
}


while (ob_get_level()) {
  ob_end_clean();
}

while (true) {
  conectar();
}
