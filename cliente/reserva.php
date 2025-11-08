<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");

$usuario = $_SESSION["dni"];

$m = $_GET["mesa"];

$_SESSION["mesa"] = $m;

$fechaAct = date("Y-m-d H:i:s");

$consulta = "INSERT INTO reserva (dni, numMesa, fecha) VALUES ('$usuario', '$m', '$fechaAct')";

$result = mysqli_query($conn, $consulta);

mysqli_query($conn, "UPDATE mesa SET estado = 0 WHERE numMesa = '$m'");

header("LOCATION: eligeComensales.php");
exit;
