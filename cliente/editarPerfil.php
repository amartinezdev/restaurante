<?php
session_start();

// actualizar la base de datos con el producto actualizado.
if (!isset($_SESSION["usuario"])) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $pw = $_POST["pw"];


    $dni = $_SESSION["dni"];


    if ($direccion == null) {
        $consulta = "UPDATE usuario
        SET nombre = '$nombre',
            apellido = '$apellido',
            password = '$pw',
            email = '$email',
            telefono = '$telefono',
            direccion = null
        WHERE dni = '$dni'";
    } else {
        $consulta = "UPDATE usuario
        SET nombre = '$nombre',
            apellido = '$apellido',
            password = '$pw',
            email = '$email',
            telefono = '$telefono',
            direccion = '$direccion'
        WHERE dni = '$dni'";
    }


    $result = mysqli_query($conn, $consulta);

    mysqli_close($conn);

    if ($nombre != $_SESSION["usuario"]) {
        $_SESSION["usuario"] = $nombre;
    }

    $_SESSION["mensaje"] = "<p class='text-success infosuccess text-center'>Has cambiado los datos con éxito</p>";
    header("LOCATION: /restaurante/cliente/perfil.php");
    exit;
}
