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

            //Metemos los span dentro de <li>
            item.appendChild(nombre);
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
}

function ocultarMensaje(){
    document.getElementById('mensaje').hidden = true;
}

cargarCategorias();