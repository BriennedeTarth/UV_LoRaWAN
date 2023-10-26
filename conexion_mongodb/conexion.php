<?php

require_once "vendor/autoload.php";

class conexion
{
    public function conectar()
    {
        try {
            $servidor = "127.0.0.1";
            $usuario = "mongoadmin";
            $password = "123456";
            $basedatos = "crud";
            $puerto = "27017";
            $cadena = "mongodb://localhost:27017/crud";

            $cadenacon = "mongodb://" .
                $usuario . ":" .
                $password . "@" .
                $servidor . ":" .
                $puerto . "/" .
                $basedatos;
            $cliente = new MongoDB\Client($cadena);
            return $cliente->selectDatabase($basedatos);
        } catch (\Trowable $th) {
            return $th->getMessage();
        }
    }
}
?>