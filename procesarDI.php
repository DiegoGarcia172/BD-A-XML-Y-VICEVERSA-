<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProducto = $_POST['ID'];
    $nombreProducto = $_POST['nombredelprdocuto'];
    $cantidadProducto = $_POST['CantidadProducto'];
    $costoProducto = $_POST['CostoDelProducto'];
    $codigoBarras = $_POST['CodigoDeBarras'];
    $nombreArchivo = $_FILES['imagenProducto']['name'];
    $tipoArchivo = $_FILES['imagenProducto']['type'];
    $tamanioArchivo = $_FILES['imagenProducto']['size'];
    $tempArchivo = $_FILES['imagenProducto']['tmp_name'];
    $carpetaBase = 'imagenes/';
    $carpetaProducto = $carpetaBase . $idProducto . '/';
    if (!file_exists($carpetaProducto)) {
        mkdir($carpetaProducto, 0777, true);
    }
    $rutaImagen = $carpetaProducto . $nombreArchivo;
    move_uploaded_file($tempArchivo, $rutaImagen);
    $xmlDoc = new DOMDocument('1.0', 'utf-8');
    $xmlDoc->formatOutput = true; 
    $root = $xmlDoc->createElement('productos');
    $xmlDoc->appendChild($root);
    $producto = $xmlDoc->createElement('producto');
    $root->appendChild($producto);
    $idElemento = $xmlDoc->createElement('id', $idProducto);
    $producto->appendChild($idElemento);
    $nombreElemento = $xmlDoc->createElement('nombre', $nombreProducto);
    $producto->appendChild($nombreElemento);
    $cantidadElemento = $xmlDoc->createElement('cantidad', $cantidadProducto);
    $producto->appendChild($cantidadElemento);
    $costoElemento = $xmlDoc->createElement('costo', $costoProducto);
    $producto->appendChild($costoElemento);
    $codigoElemento = $xmlDoc->createElement('codigo_barras', $codigoBarras);
    $producto->appendChild($codigoElemento);
    $imagenElemento = $xmlDoc->createElement('imagen', $rutaImagen);
    $producto->appendChild($imagenElemento);
    $xmlDoc->save('productos.xml');
    echo 'Datos guardados exitosamente en XML.';
} else {
    echo 'Error: No se han enviado datos del formulario.';
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tarea";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$xml = simplexml_load_file("productos.xml") or die("Error al cargar el archivo XML");
foreach ($xml->producto as $producto) {
    $id = $producto->id;
    $nombre = $producto->nombre;
    $cantidad = $producto->cantidad;
    $costo = $producto->costo;
    $codigo_barras = $producto->codigo_barras;
    $imagen = $producto->imagen;
    $sql = "INSERT INTO productos (id, nombre, cantidad, costo, codigo_barras)
            VALUES ('$id', '$nombre', '$cantidad', '$costo', '$codigo_barras')";

    if ($conn->query($sql) === TRUE) {
        echo "Datos insertados correctamente en la base de datos.";
    } else {
        echo "Error al insertar datos: " . $conn->error;
    }
}
$conn->close();
?>