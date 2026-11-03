// Funciones que usan todas las pantallas

function mostrarMensaje(texto, tipo) {
    const mensaje = document.getElementById('mensaje');
    mensaje.textContent = texto;
    mensaje.className = 'mensaje mensaje-' + tipo;
    mensaje.hidden = false;
    mensaje.scrollIntoView({ block: 'nearest' });
}

function ocultarMensaje() {
    document.getElementById('mensaje').hidden = true;
}