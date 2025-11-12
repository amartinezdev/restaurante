<?php
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}
