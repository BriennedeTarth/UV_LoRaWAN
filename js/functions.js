function enviarFormulario(formID) {
    document.forms[formID].submit();
}

function verificarFormulario() {
    const nombre = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const mensaje = document.getElementById('message').value;

    if (nombre === '' || email === '' || mensaje === '') {
        // Detener el envío del formulario
        event.preventDefault();
        // Mostrar alerta si hay campos vacíos
        Swal.fire({
            icon: 'error',
            text: "One or more fields are empty.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Accept"
        });
    }
}

function toggleMenu() {
    const menu_cont = document.querySelector('.menu-cont');
    const menu = document.querySelector('.menu');
    menu_cont.classList.toggle('active'); // Agregar/eliminar clase 'active' para mostrar/ocultar el sidebar
    menu.classList.toggle('active');
}

