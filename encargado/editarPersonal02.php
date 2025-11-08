<?php
session_start();

// actualizar la base de datos con el producto actualizado.
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $bloqueado = $_POST["bloqueado"];
    $rol = $_POST["rol"];

    if ($rol == "encargado") {
        $rol = 2;
    } else if ($rol == "camarero") {
        $rol = 1;
    }


    if ($bloqueado == "si") {
        $bloqueado = 1;
    } else {
        $bloqueado = 0;
    }
}


$idPersonal = $_SESSION["idPersonal"];

$consulta = "UPDATE usuario
SET nombre = '$nombre',
    apellido = '$apellido',
    email = '$email',
    estado = '$bloqueado',
    telefono = '$telefono',
    rol = '$rol'
WHERE dni = '$idPersonal'";

$result = mysqli_query($conn, $consulta);

mysqli_close($conn);

header("LOCATION: /restaurante/encargado/personal.php");
exit;
