<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once __DIR__ . "/../components/demo.php";
    if (DEMO_MODE && demo_cuentas_creadas($conn) >= DEMO_MAX_CUENTAS_NUEVAS) {
        demo_block('limite_cuentas');
        header("LOCATION: /encargado/addPersonal.php");
        exit;
    }

    $dni = $_POST["dni"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $estado = $_POST["bloqueado"];
    $rol = $_POST["rol"];
    $pw = $_POST["pw"];

    if ($estado == "si") {
        $bloqueado = 0;
    } else {
        $bloqueado = 1;
    }

    if ($rol == "camarero") {
        $rol = 1;
    } else if ($rol == "encargado") {
        $rol = 2;
    }

    if ($direccion == "") {
        $direccion = NULL;
    }

    $consulta = "INSERT INTO usuario (dni, password, nombre, apellido, rol, email, telefono, direccion, estado)
    VALUE ('$dni', '$pw', '$nombre', '$apellido', '$rol', '$email', '$telefono', '$direccion', '$estado')";

    mysqli_query($conn, $consulta);

    mysqli_close($conn);

    header("LOCATION: personal.php");
}
