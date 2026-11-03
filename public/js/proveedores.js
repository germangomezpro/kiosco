// ===== Estado del formulario =====

const formulario = document.getElementById('form-proveedor');

// null = agregando; un número = editando ese proveedor
let idEditando = null;


// ===== Listar =====

async function cargarProveedores() {
    const lista = document.getElementById('lista-proveedores');
    ocultarMensaje();

    try {
        const respuesta = await fetch('../api/index.php?recurso=proveedores');

        if (!respuesta.ok) {
            throw new Error('La API respondió ' + respuesta.status);
        }

        const proveedores = await respuesta.json();

        lista.replaceChildren();

        if (proveedores.length === 0) {
            mostrarMensaje('Todavía no hay proveedores cargados.', 'aviso');
            return;
        }

        for (const proveedor of proveedores) {
            const item = document.createElement('li');
            item.className = 'lista-item';

            // Columna de texto: nombre, detalle y acciones
            const texto = document.createElement('div');
            texto.className = 'lista-texto';

            const nombre = document.createElement('span');
            nombre.className = 'lista-nombre';
            nombre.textContent = proveedor.nombre;
            texto.appendChild(nombre);

            // Teléfono: un enlace para llamar, solo si hay
            if (proveedor.telefono) {
                const detalleTelefono = document.createElement('p');
                detalleTelefono.className = 'lista-detalle';

                const enlace = document.createElement('a');
                enlace.href = 'tel:' + proveedor.telefono.replace(/[^\d+]/g, '');
                enlace.textContent = proveedor.telefono;

                detalleTelefono.appendChild(enlace);
                texto.appendChild(detalleTelefono);
            }

            // Observaciones: solo si hay
            if (proveedor.observaciones) {
                const detalleObservaciones = document.createElement('p');
                detalleObservaciones.className = 'lista-detalle';
                detalleObservaciones.textContent = proveedor.observaciones;
                texto.appendChild(detalleObservaciones);
            }

            // Acciones
            const acciones = document.createElement('div');
            acciones.className = 'lista-acciones';

            const botonEditar = document.createElement('button');
            botonEditar.type = 'button';
            botonEditar.className = 'accion';
            botonEditar.textContent = 'Editar';
            botonEditar.addEventListener('click', function () {
                empezarEdicion(proveedor);
            });

            const botonEliminar = document.createElement('button');
            botonEliminar.type = 'button';
            botonEliminar.className = 'accion accion-peligro';
            botonEliminar.textContent = 'Eliminar';
            botonEliminar.addEventListener('click', function () {
                eliminarProveedor(proveedor);
            });

            acciones.appendChild(botonEditar);
            acciones.appendChild(botonEliminar);
            texto.appendChild(acciones);

            item.appendChild(texto);
            lista.appendChild(item);
        }
    } catch (error) {
        console.error(error);
        mostrarMensaje('No se pudieron cargar los proveedores. Revisá la conexión y volvé a cargar la página.', 'error');
    }
}


// ===== Editar =====

function empezarEdicion(proveedor) {
    idEditando = proveedor.id;

    document.getElementById('nombre').value = proveedor.nombre;
    document.getElementById('telefono').value = proveedor.telefono ?? '';
    document.getElementById('observaciones').value = proveedor.observaciones ?? '';

    document.getElementById('boton-guardar').textContent = 'Guardar cambios';
    document.getElementById('cancelar-edicion').hidden = false;

    ocultarMensaje();
    formulario.scrollIntoView({ block: 'nearest' });
    document.getElementById('nombre').focus();
}

function terminarEdicion() {
    idEditando = null;
    formulario.reset();

    document.getElementById('boton-guardar').textContent = 'Agregar proveedor';
    document.getElementById('cancelar-edicion').hidden = true;
}

document.getElementById('cancelar-edicion').addEventListener('click', terminarEdicion);


// ===== Agregar o guardar cambios =====

formulario.addEventListener('submit', async function (evento) {
    evento.preventDefault();
    ocultarMensaje();

    const editando = idEditando !== null;

    // Para agregar: POST, con los datos como formulario
    let direccion = '../api/index.php?recurso=proveedores';
    let opciones = {
        method: 'POST',
        body: new FormData(formulario)
    };

    // Para modificar: PUT, con el id en la dirección y los datos en JSON
    if (editando) {
        direccion = direccion + '&id=' + idEditando;
        opciones = {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nombre: document.getElementById('nombre').value,
                telefono: document.getElementById('telefono').value,
                observaciones: document.getElementById('observaciones').value
            })
        };
    }

    try {
        const respuesta = await fetch(direccion, opciones);
        const resultado = await respuesta.json();

        if (!respuesta.ok) {
            mostrarMensaje(resultado.error, 'error');
            return;
        }

        terminarEdicion();
        document.getElementById('nombre').focus();

        await cargarProveedores();

        if (editando) {
            mostrarMensaje('Cambios guardados.', 'aviso');
        } else {
            mostrarMensaje('Proveedor agregado.', 'aviso');
        }
    } catch (error) {
        console.error(error);
        mostrarMensaje('No se pudo guardar el proveedor. Revisá la conexión y probá de nuevo.', 'error');
    }
});


// ===== Eliminar =====

async function eliminarProveedor(proveedor) {
    const seguro = confirm('¿Eliminar el proveedor "' + proveedor.nombre + '"?');

    if (!seguro) {
        return;
    }

    ocultarMensaje();

    try {
        const respuesta = await fetch('../api/index.php?recurso=proveedores&id=' + proveedor.id, {
            method: 'DELETE'
        });

        const resultado = await respuesta.json();

        if (!respuesta.ok) {
            mostrarMensaje(resultado.error, 'error');
            return;
        }

        await cargarProveedores();
        mostrarMensaje('Proveedor eliminado.', 'aviso');
    } catch (error) {
        console.error(error);
        mostrarMensaje('No se pudo eliminar el proveedor. Revisá la conexión y probá de nuevo.', 'error');
    }
}


// ===== Al abrir la página =====

cargarProveedores();