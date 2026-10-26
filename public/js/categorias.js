async function cargarCategorias(){
    const respuesta = await fetch ('../api/index.php?recurso=categorias');
    const categorias = await respuesta.json();

    //Tomamos el ul con el id 'lista-categorias'
    const lista = document.getElementById('lista-categorias');
    //Vaciamos la lista
    lista.replaceChildren();

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
}

cargarCategorias();