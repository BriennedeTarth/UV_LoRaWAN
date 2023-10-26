<!DOCTYPE html>
<html>

<head>
   <!--Metadatos para reconocer cuando es un dispositivo movil-->
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!--Icono en las páginas-->
   <link rel="icon" type="image/x-icon" href="../../../img/smart-lab.ico">
   <!--Hoja de estilos-->
   <link rel="stylesheet" type="text/css" href="../style/styles.css">
   <link rel="stylesheet" type="text/css" href="../style/responsive.css">
   <!--Importación de la libreria chart.js-->
   <script src="../../../js/chart.js"></script>
   <!--Importación de libreria y hoja de estilos de "Sweetalert"-->
   <script src="../../../js/sweetalert.js"></script>
   <link rel="stylesheet" type="text/css" href="/../style/sweetalert.css">
   <!--Archivo de datos js-->
   <script src="../../../js/datos.js"></script>
   <!--Tilo de las páginas-->
   <title>SmartLab Monitoring</title>
</head>

<body>
   <header class="header">
      <div class="menu-toggle" onclick="toggleMenu()">☰</div>
      <div class="logo">
         <a href="smart-lab?modulo=home.php">
            <img src="../img/Header/logo.png" alt="Logo">
         </a>
      </div>
      <h1><a href="smart-lab?modulo=home.php">SMARTLAB<br>Monitoring</a></h1>
      <nav class="menu-cont">
         <div class="menu-toggle-close" onclick="toggleMenu()">✕</div>
         <ul class="menu" id="menu">
            <li><a href="smart-lab?module=home.php" <?php if ($page != 'contact.php' && $page != 'about-us.php')
               echo 'class="active"'; ?>><img src="../img/Header/home.png" class="header-img">Home</a></li>
            <li><a href="smart-lab?module=contact.php" <?php if ($page == 'contact.php')
               echo 'class="active"'; ?>><img src="../img/Header/contact-us.png" class="header-img">Contact</a></li>
            <li><a href="smart-lab?module=about-us.php" <?php if ($page == 'about-us.php')
               echo 'class="active"'; ?>><img src="../img/Header/about-us.png" class="header-img">About
                  Us</a></li>
         </ul>
      </nav>
   </header>
</body>

</html>