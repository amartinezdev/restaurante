<?php
session_start();
include("../components/conexion.php");
include("seguridad.php");

$idCategoria = $_GET["id"];

$consulta = mysqli_query($conn, "UPDATE categoria SET estado = 1 WHERE id = '$idCategoria'");
$consultaPedido = mysqli_query($conn, "UPDATE producto SET estado_categoria = 1 WHERE categoria = '$idCategoria'");

header("LOCATION: categorias.php");
exit;
