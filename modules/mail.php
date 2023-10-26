<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Recibir datos de formulario
    $email = $_POST['email'];
    $nombre = $_POST['name'];
    $mensaje = $_POST['message'];
    //Cuenta de destino
    $destino = 'brandon.campoverde@epn.edu.ec';
    //Asunto
    $asunto = 'Contact Us Smart-Lab Monitoring';
    //Cuerpo del mensaje
// Cuerpo del correo
    $cuerpo = '
<html>
<body>
<h3>Nombre: ' . $nombre . '</h3><br>
<h3>Email: ' . $email . '</h3><br>
<h3>Mensaje: ' . $mensaje . '</h3>
</body>
</html>
';
    //Encabezados
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charser=utf-8\r\n";
    $headers .= "From:$nombre <$email>\r\n";

    //Envio de correo
    if (mail($destino, $asunto, $cuerpo, $headers)) {
        echo '<script type="text/javascript">';
        echo 'Swal.fire({';
        echo '    icon: "success",';
        echo '    title: "¡Mail sent successfully!",';
        echo '    text: "Thanks for contacting us.",';
        echo '    confirmButtonColor: "#3085d6",';
        echo '    confirmButtonText: "Accept"';
        echo '}).then((result) => {';
        echo '    if (result.isConfirmed) {';
        echo '        window.location.href = "smart-lab?module=contact.php";';
        echo '    }';
        echo '});';
        echo '</script>';
    } else {
        echo '<script type="text/javascript">';
        echo 'Swal.fire({';
        echo '    icon: "error",';
        echo '    title: "Oops...",';
        echo '    text: "The mail was not sent.",';
        echo '    confirmButtonColor: "#3085d6",';
        echo '    confirmButtonText: "Accept"';
        echo '}).then((result) => {';
        echo '    if (result.isConfirmed) {';
        echo '        window.location.href = "smart-lab?module=contact.php";';
        echo '    }';
        echo '});';
        echo '</script>';
    }
}
?>
<!DOCTYPE html>
<html>

<body>
    <div class="contact">
        <div class="contact-image">
            <img src="../Img/Contact/smart-lab.jpg">
        </div>

        <div class="form-contact">
            <form method="post" action="smart-lab?module=mail.php">
                <h2>Contact Us</h2>
                <label for="name">Name:</label><br>
                <input type="text" name="name" id="name" placeholder="Ex. Brandon Campoverde" required></input><br><br>
                <label for="email">Mail:</label><br>
                <input type="email" name="email" id="email" placeholder="Ex. mail@example.com" required></input><br><br>
                <label for="message">Messagge:</label><br>
                <textarea name="message" id="message" required></textarea>
                <div class="button-container">
                    <button type="submit" value="Subtmit" id="enviar-btn"
                        onclick="verificarFormulario()">Subtmit</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>