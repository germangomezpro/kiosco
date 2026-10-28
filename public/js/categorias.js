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
        
        //Comprobamos si hay categorías cargadas en base de datos.
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
                empezarEdicion(categoria);
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

let idEditando = null;

function empezarEdicion(categoria){
    idEditando = categoria.id;

    document.getElementById('nombre').value = categoria.nombre;
    document.getElementById('margen'). value = Number(categoria.margen).toLocaleString('es-AR');

    document.getElementById('boton-guardar').textContent = 'Guardar cambios';
    document.getElementById('cancelar-edicion').hidden = false;

    ocultarMensaje();
    formulario.scrollIntoView({block: 'nearest'});
    document.getElementById('nombre').focus();
}


function terminarEdicion(){
    idEditando = null;
    formulario.reset();
    
    document.getElementById('boton-guardar').textContent = 'Agregar categoría';
    document.getElementById('cancelar-edicion').hidden = true;

}

document.getElementById('cancelar-edicion').addEventListener('click', terminarEdicion);



formulario.addEventListener('submit', async function (evento){
    // Que el formulario no recargue la página
    evento.preventDefault();
    // Escondemos el mensaje del intento anterior
    ocultarMensaje();

    // Anotamos si estamos editando (true) o agregando (false)
    const editando = idEditando !== null;

    // Preparamos los dos parámetros del fetch, para AGREGAR (POST)
    let direccion = '../api/index.php?recurso=categorias';
    let opciones = {
        method: 'POST',
        body: new FormData(formulario)
    };

    // Reemplazamos los parámetros si es que es una edición de categoría
    if(editando){
        //En este caso la dirección lleva el id de la categoria que se edita
        direccion = direccion + '&id=' + idEditando;
        opciones = {
            method: 'PUT',
            //Se avisa a la API que el cupero va en JSON
            headers: {'Content-Type': 'application/json'},
            //Armar el JSON con lo escrito en los campos
            body: JSON.stringify({
                nombre: document.getElementById('nombre').value,
                margen: document.getElementById('margen').value,
            })
        };
    }

    try {
        const respuesta = await fetch(direccion, opciones);
        const resultado = await respuesta.json();

        //Mostrar si la API respondió con error y cortar
        if(!respuesta.ok){
            mostrarMensaje(resultado.error, 'error');
            return;
        }

        //Si salió bien, vaciar los campos y volver al modo agregar

        terminarEdicion();
        document.getElementById('nombre').focus();

        //Volver a pedir las categorías ya con el cambio
        await cargarCategorias();

        //El mensaje según lo que se hizo (editar o crear)
        if(editando){
            mostrarMensaje('Cambios guardados', 'aviso');
        }else{
            mostrarMensaje('Categoría agregada', 'aviso');
        }
        
    } catch (error) {
        //Sin conexión, o una respuesta que no es JSON
        console.error(error);
        mostrarMensaje('No se pudo guardar la categoría. Revise la conexión y vuelva a intentar');    
    }
});