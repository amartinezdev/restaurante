<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");
$navbar1 = "/restaurante/cliente/cliente.php";
$navbar2 = "/restaurante/cliente/pedidos.php";
$navbar3 = "/restaurante/cliente/perfil.php";
$active = "perfil";
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Cliente</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <!-- HEADER + NAV -->
        <?php
        include("../components/header.php");
        include("../components/navbar.php");
        $usuario = $_SESSION["usuario"];
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">

        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>