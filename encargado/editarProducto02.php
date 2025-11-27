<?php
session_start();

// actualizar la base de datos con el producto actualizado.
include("seguridad.php");
include("../components/conexion.php");

$idProducto = $_SESSION["idProducto"];
echo $idProducto;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $bloqueado = $_POST["bloqueado"];
    $cat = $_POST["cat"];


    if ($bloqueado == "si") {
        $bloqueado = 0;
    } else {
        $bloqueado = 1;
    }
}


$producto = $_SESSION["idProducto"];

$consulta = "UPDATE producto
SET nombre = '$nombre',
    precio = '$precio',
    stock = '$stock',
    estado = '$bloqueado'
WHERE id = '$producto'";

$result = mysqli_query($conn, $consulta);

$consulta = "SELECT * FROM producto WHERE id = '$idProducto'";

if ($idProducto < 10) {
    $ima = "../img_productos/00" . $idProducto . ".png";
} else if ($idProducto < 100) {
    $ima = "../img_productos/0" . $idProducto . ".png";
} else {
    $ima = "../img_productos/" . $idProducto . ".png";
}

// Si el usuario ha subido una imagen nueva
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {

    // Guardar el archivo en el servidor
    COPY($_FILES["imagen"]["tmp_name"], $ima);

    // Actualizar la columna imagen
    $consulta = "UPDATE producto
        SET imagen = '$ima'
        WHERE id = '$idProducto'";
    $resultado2 = mysqli_query($conn, $consulta);
}

mysqli_close($conn);

header("LOCATION: /encargado/encargado.php");
exit;
