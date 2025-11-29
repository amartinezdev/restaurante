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
    $pw = $_POST["pw"];


    $dni = $_SESSION["dni"];

    require_once __DIR__ . "/../components/demo.php";
    if (DEMO_MODE) {
        $actual = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM usuario WHERE dni = '$dni'"))["password"];
        if ($pw !== $actual) {
            demo_block('password');
        }
        $pw = $actual;
    }

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
            direccion = $direccion
        WHERE dni = '$dni'";
    }


    $result = mysqli_query($conn, $consulta);

    mysqli_close($conn);

    $_SESSION["mensaje"] = "<p class='text-success infosuccess text-center'>Has cambiado los datos con éxito</p>";
    header("LOCATION: /encargado/perfil.php");
    exit;
}
