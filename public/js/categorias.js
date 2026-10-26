async function cargarCategorias(){
    const respuesta = await fetch ('../api/index.php?recurso=categorias');
    const categorias = await respuesta.json();

    console.log(categorias);
}

cargarCategorias();