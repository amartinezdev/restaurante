<?php
session_start();

include("../components/conexion.php");

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}

// funcionalidad para añadir el producto a la base de datos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $bloqueado = $_POST["bloqueado"];

    if ($bloqueado == "si") {
        $bloqueado = 0;
    } else {
        $bloqueado = 1;
    }

    $cat = $_POST["cat"];

    $consulta2 = "SELECT id FROM categoria WHERE nombre = '$cat'";
    $result = mysqli_query($conn, $consulta2);

    $row = mysqli_fetch_array($result);

    $cat = $row["id"];

    $consulta = "INSERT INTO producto (nombre, precio, stock, estado, categoria)
            VALUES('$nombre', '$precio', '$stock', '$bloqueado', '$cat')";

    $result = mysqli_query($conn, $consulta);

    mysqli_close($conn);

    header("LOCATION: /restaurante/encargado/encargado.php");
    exit;
}
