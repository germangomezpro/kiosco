async function cargarCategorias(){
    const respuesta = await fetch ('../api/index.php?recurso=categorias');
    const categorias = await respuesta.json();

    //Tomamos el ul con el id 'lista-categorias'
    const lista = document.getElementById('lista-categorias');
    //Vaciamos la lista
    lista.replaceChildren();

    for(const categoria of categorias){
        //Creamos los <li></li>
        const item = document.createElement('li');
        //Le damos formato al margen
        const margen = Number(categoria.margen).toLocaleString('es-AR');

        //Metemos el contenido en los <li>
        item.textContent = categoria.nombre + ' - ' + margen + '%';
        lista.appendChild(item);
    }
}

cargarCategorias();