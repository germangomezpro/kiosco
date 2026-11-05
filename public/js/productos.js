// Listar

async function cargarProductos(){
    const lista = document.getElementById('lista-productos');

    ocultarMensaje();

    //Traemos todos los productos
    try{
        const respuesta = await fetch('../api/index.php?recurso=productos');

        //Comprobamos no hubo errores en la respuesta de la API
        if(!respuesta.ok){
            throw new Error('La API respondió ' + respuesta.status);
        }

        //Recibimos la respuesta de la API
        const productos = await respuesta.json();

        //Limpiamos la lista
        lista.replaceChildren();

        //Comprobamos si hay productos a mostrar
        if(productos.length === 0){
            mostrarMensaje('No hay productos para mostrar.' , 'aviso');
        }

        //Recorremos los productos
        for(const producto of productos){
            const item = document.createElement('li');
            item.className = 'lista-item';

            //Columna de texto: nombre y detalle
            const texto = document.createElement('div');
            texto.className = 'lista-texto';

            const nombre = document.createElement('span');
            nombre.className = 'lista-nombre';
            nombre.textContent = producto.nombre;
            texto.appendChild(nombre);

            //Detalle: categoría y proveedor, en una linea.
            const detalle = document.createElement('p');
            detalle.className = 'lista-detalle';
            detalle.textContent = producto.categoria + ' · ' + producto.proveedor;
            texto.appendChild(detalle);

            //El precio, en la etiqueta amarilla
            const precio = document.createElement('span');
            precio.className = 'lista-numero';
            precio.textContent = '$ ' + Number(producto.precio_venta).toLocaleString('es-AR');

            //Si se vende por kilo, se aclara
            if(producto.unidad_medida === 'kg'){
                precio.textContent = precio.textContent + ' /kg';
            }

            item.appendChild(texto);
            item.appendChild(precio);
            lista.appendChild(item);

        }
    }catch(error){
        console.error(error);
        mostrarMensaje('No se pudieron cargar los productos. Revise su conexión e intente nuevamente', 'error');
    }
}


// Al abrir la página

cargarProductos();