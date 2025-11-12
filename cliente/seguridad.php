<?php

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 0) {
    header("LOCATION: ../index.php");
    exit;
}
