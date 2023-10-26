<?php
require_once "conexion.php";

class crud extends conexion
{

    public function mostrardatos($collecction, $consulta)
    {
        try {
            $conexion = parent::conectar();
            $coleccion = $conexion->selectCollection($collecction);
            $consulta = ['date' => ['$in' => $consulta]];
            $datos = $coleccion->find($consulta);
            return $datos;
        } catch (\Trowable $th) {
            return $th->getMessage();
        }
    }

    public function mostrarultimodato($collecction)
    {
        try {
            $conexion = parent::conectar();
            $coleccion = $conexion->selectCollection($collecction);
            $datos = $coleccion->findOne([], ['sort' => ['_id' => -1]]);
            return $datos;
        } catch (\Trowable $th) {
            return $th->getMessage();
        }
    }

    public function insertardatos($datos)
    {
        try {
            $conexion = parent::conectar();
            $coleccion = $conexion->datos_epn;
            $resultado = $coleccion->insertOne($datos);
            return $resultado;
        } catch (\Trowable $th) {
            return $th->getMessage();
        }
    }
}
?>