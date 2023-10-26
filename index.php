<?php
// Leemos la variable que indica que página queremos cargar.
if(isset($_GET['module'])){
  if (empty($_GET['module']) || !file_exists('modules/'.$_GET['module'])) {
    $page = 'error.php';
  } else {
    $page = $_GET['module'];
  }
}else{
  $page='home.php';
}

include('include/header.php');
include('modules/' . $page);
include('include/footer.php');
?>