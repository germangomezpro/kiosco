<!-- Página para probar funcionalidad -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba</title>
</head>
<body>
    <form action="../api/index.php?recurso=categorias" method="post">
        <input type="text" name="nombre" placeholder="Nombre">
        <input type="text" name="margen" placeholder="Margen">
        <button type="submit">Crear</button>
    </form>
</body>
</html>