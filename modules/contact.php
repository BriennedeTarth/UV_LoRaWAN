<!DOCTYPE html>
<html>

<body>
   <div class="contact">
      <div class="contact-image">
         <img src="../img/Contact/smart-lab.jpg">
      </div>

      <div class="form-contact">
         <form method="post" action="smart-lab?module=mail.php">
            <h2>Contact Us</h2>
            <label for="name">Name:</label><br>
            <input type="text" name="name" id="name" placeholder="Ex. Lola Ramos"></input><br><br>
            <label for="email">Mail:</label><br>
            <input type="email" name="email" id="email" placeholder="Ex. mail@example.com"></input><br><br>
            <label for="message">Messagge:</label><br>
            <textarea name="message" id="message"></textarea>
            <div class="button-container">
               <button type="submit" value="Subtmit" id="enviar-btn" onclick="verificarFormulario()">Subtmit</button>
            </div>
         </form>
      </div>
   </div>
</body>

</html>