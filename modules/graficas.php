<!DOCTYPE html>
<html>

<?php
require_once "./conexion_mongodb/conexion.php";
require_once "./conexion_mongodb/crud.php";
$con = new crud();
$coleccion = $_POST['coleccion'];
$metrica = $_POST['tipo_dato'];
$place = $_POST['place'];
$title = ucfirst($metrica);
$datos = $con->mostrarultimodato($coleccion);
$date = $datos->date;
?>

<body onload="graficar(['<?php echo $date ?>'],'<?php echo $coleccion ?>','<?php echo $metrica ?>')">

  <div class="containergraf">
    <a href="smart-lab?module=home.php"><img src="../../../img/module/back.png" alt="back"></a>
    <h2>
      <?php echo $title; ?><br>
      <?php echo $place; ?>
    </h2>
    <div class="calendario">
      <?php
      include("include/calendar.php")
        ?>
    </div>
    <div id="graphics">
    </div>
  </div>

  <div class="clearfix"></div>

  <script type="text/javascript" src="../../../js/datos.js"></script>

</body>

</html>