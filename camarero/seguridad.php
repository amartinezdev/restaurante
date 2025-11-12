<?php
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 1) {
    header("LOCATION: ../index.php");
    exit;
}
