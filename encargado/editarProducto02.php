<?php
session_start();

// actualizar la base de datos con el producto actualizado.
include("seguridad.php");

include("../components/conexion.php");


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

mysqli_close($conn);

header("LOCATION: /restaurante/encargado/encargado.php");
exit;
