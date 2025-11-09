<?php

if (!isset($_SESSION["usuario"])) {
    header("LOCATION: ../index.php");
    exit;
}


if (!isset($_SESSION["haElegidoMesa"])) {
    header("LOCATION: mesa.php");
} else if (!isset($_SESSION["haElegidoComensales"])) {
    header("LOCATION: eligeComensales.php");
}
