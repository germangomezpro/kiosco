async function cargarCategorias(){
    //Tomamos el ul con el id 'lista-categorias'
    const lista = document.getElementById('lista-categorias');
    //Ocultamos mensajes por si es un error antiguo
    ocultarMensaje();

    try {
        // Fetch a la API    
        const respuesta = await fetch ('../api/index.php?recurso=categorias');

        //Comprobamos si no hubo un error en la API
        if(!respuesta.ok){
            throw new Error('La API respondió ' + respuesta.status);
        }

        //Formato json a array
        const categorias = await respuesta.json();

        //Vaciamos la lista
        lista.replaceChildren();
        
        //Comprobamos si hay categorías cargadas
        if(categorias.length === 0){
            mostrarMensaje('Todavía no hay categorías cargadas.', 'aviso');
            return;
        }

        for(const categoria of categorias){
            //Creamos los <li></li>, y le agregamos una clase
            const item = document.createElement('li');
            item.className = 'lista-item';

            //Creamos un span para el nombre
            const nombre = document.createElement('span');
            nombre.className = 'lista-nombre';
            nombre.textContent = categoria.nombre;

            //Le damos formato al margen y lo metemos en un span con una clase
            const margen = document.createElement('span');
            margen.className = 'lista-numero';
            margen.textContent = Number(categoria.margen).toLocaleString('es-AR') + ' %';

            //Acciones de la fila
            const acciones = document.createElement('div');
            acciones.className = 'lista-acciones';

            //Botón Editar
            const botonEditar = document.createElement('button');
            botonEditar.type = 'button';
            botonEditar.className = 'accion';
            botonEditar.textContent = 'Editar';
            botonEditar.addEventListener('click', function(){
                console.log('Editar', categoria.id, categoria.nombre);
            });

            //Botón Eliminar
            const botonEliminar = document.createElement('button');
            botonEliminar.type = 'button';
            botonEliminar.className = 'accion accion-peligro';
            botonEliminar.textContent = 'Eliminar';
            botonEliminar.addEventListener('click', function(){
                eliminarCategoria(categoria);
            });

            acciones.appendChild(botonEditar);
            acciones.appendChild(botonEliminar);

            //El nombre y las acciones, uno arriba del otro
            const texto = document.createElement('div');
            texto.className = 'lista-texto';
            texto.appendChild(nombre);
            texto.appendChild(acciones);

            //Metemos los span dentro de <li>
            item.appendChild(texto);
            item.appendChild(margen);
            //Y los li dentro de ul
            lista.appendChild(item);

        }   
    } catch (error) {
        console.error(error); //Mandamos a consola el error técnico.
        mostrarMensaje('No se pudieron cargar las categorías. Revisá la conexión y volvé a cargar la página.', 'error');
    }
}


function mostrarMensaje(texto, tipo){
    const mensaje = document.getElementById('mensaje');
    mensaje.textContent = texto;
    mensaje.className = 'mensaje mensaje-' + tipo;
    mensaje.hidden = false;
    mensaje.scrollIntoView({ block: 'nearest' });
}

function ocultarMensaje(){
    document.getElementById('mensaje').hidden = true;
}

//Función para eliminar categoria
async function eliminarCategoria(categoria){
    //Preguntamos al usuario si está seguro
    const seguro = confirm('¿Eliminar la categoría "' + categoria.nombre + '"?');

    if(!seguro){
        return;
    }

    ocultarMensaje();

    try{
        const respuesta = await fetch('../api/index.php?recurso=categorias&id=' + categoria.id, {
            method: 'DELETE'
        });

        const resultado = await respuesta.json();

        if(!respuesta.ok){
            mostrarMensaje(resultado.error, 'error');
            return;
        }

        await cargarCategorias();
        mostrarMensaje('Categoría eliminada.', 'aviso');
    }catch(error){
        console.error(error);
        mostrarMensaje('No se pudo eliminar la categoría. Revise la conexión y vuelva a intentar', 'error');
    }
}

cargarCategorias();

// Enviar datos del formulario a la base de datos

const formulario = document.getElementById('form-categoria');


formulario.addEventListener('submit', async function (evento){
    evento.preventDefault();
    ocultarMensaje();

    try{
        const respuesta = await fetch ('../api/index.php?recurso=categorias',{
            method: 'POST',
            body: new FormData(formulario)
        });

        //Verificamos que la API no responda con error.
        const resultado = await respuesta.json();

        if(!respuesta.ok){
            mostrarMensaje(resultado.error, 'error');
            return;
        }

        //Reseteamos el formulario
        formulario.reset();
        //Deja el cursor en el nombre, listo para cargar la siguiente.
        document.getElementById('nombre').focus();

        //Mostramos las categorias con la nueva agregada
        await cargarCategorias();
        mostrarMensaje('Categoría agregada.', 'aviso');
    }catch(error){
        console.error(error);
        mostrarMensaje('No se pudo guardar la categoría. Revise la conexión y pruebe nuevamente.', 'error');
    }
});