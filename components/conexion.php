<?php
// conexion
$servidor = "localhost";
$user = "root";
$clave = "";
$basededatos = "restaurante";

// Establecimiento de la conexión al servidor localhost,
$conn = mysqli_connect($servidor, $user, $clave);

// Seleccionamos la BBDD
mysqli_select_db($conn, $basededatos);

// Imprimimos si hay algún error
echo mysqli_error($conn);
