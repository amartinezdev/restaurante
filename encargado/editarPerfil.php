<?php
session_start();

// actualizar la base de datos con el producto actualizado.
include("seguridad.php");

include("../components/conexion.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];


    $dni = $_SESSION["dni"];


    if ($direccion == null) {
        $consulta = "UPDATE usuario
        SET nombre = '$nombre',
            apellido = '$apellido',
            email = '$email',
            telefono = '$telefono',
            direccion = null
        WHERE dni = '$dni'";
    } else {
        $consulta = "UPDATE usuario
        SET nombre = '$nombre',
            apellido = '$apellido',
            email = '$email',
            telefono = '$telefono',
            direccion = $direccion
        WHERE dni = '$dni'";
    }


    $result = mysqli_query($conn, $consulta);

    mysqli_close($conn);

    $_SESSION["mensaje"] = "<p class='text-success infosuccess text-center'>Has cambiado los datos con éxito</p>";
    header("LOCATION: /restaurante/encargado/perfil.php");
    exit;
}
