<?php
session_start();
include("../conexion.php");
include("seguridad.php");

$dni = $_SESSION["dni"];

$producto = $_GET["id"];
