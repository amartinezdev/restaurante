<?php
session_start();

// funcionalidad para bloquear el producto
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");

$id = $_GET["id"];

$consulta = "UPDATE usuario
SET estado = 0
WHERE dni = '$id'";

$result = mysqli_query($conn, $consulta);

mysqli_close($conn);

header("LOCATION: /restaurante/encargado/personal.php");
exit;
