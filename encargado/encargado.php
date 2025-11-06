<?php
session_start();

include("../components/conexion.php");

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}

$active = "productos";
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Cliente</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="../styles.css" />
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <!-- HEADER + NAV -->
        <?php
        include("../components/header.php");
        include("navbar.php");
        $usuario = $_SESSION["usuario"];
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12">
                    <section class="bg-dark-subtle border rounded-4 p-4 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="h4 mb-1">Bienvenido, <?php echo $usuario; ?></h4>
                            <p class="lead">Consulta tus productos</p>
                        </header>
                        <section class="row justify-content-center ">
                            <div class="col-12 table-responsive">
                                <table class="table text-start text-md-center table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class='d-none d-sm-block d-sm-'>#</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                            <th>Categoria</th>
                                            <th colspan="2">Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $consulta = "SELECT * FROM producto";

                                        $result = mysqli_query($conn, $consulta);

                                        while ($row = mysqli_fetch_array($result)) {
                                            $id = $row['id'];
                                            $estado = $row["estado"];

                                            if ($estado == 1) {
                                                print("<tr>");
                                            } else {
                                                print("<tr class='table-danger'>");
                                            }
                                            print("<td class='d-none d-sm-table-cell'>");
                                            print($row['id']);
                                            print("</td>");
                                            print("<td>");
                                            print($row['nombre']);
                                            print("</td>");
                                            print("<td>");
                                            print($row['precio']);
                                            print("</td>");
                                            print("<td>");
                                            print($row['stock']);
                                            print("</td>");
                                            print("<td>");

                                            $cat = $row["categoria"];

                                            $consulta2 = "SELECT nombre FROM categoria WHERE id = '$cat'";
                                            $result2 = mysqli_query($conn, $consulta2);

                                            $row2 = mysqli_fetch_array($result2);

                                            print($row2["nombre"]);

                                            print("</td>");
                                            print("<td>");
                                            print("<a href='editarProducto.php?id=$id' class='btn btn-primary'>Editar</a>");
                                            print("</td>");
                                            print("<td class='d-none d-md-table-cell'>");
                                            if ($estado == 1) {
                                                print("<a href='bloquearProducto.php?id=$id' class='btn btn-danger'>Bloquear</a>");
                                            } else {
                                                print("<a href='desbloquearProducto.php?id=$id' class='btn btn-success'>Desloquear</a>");
                                            }

                                            print("</td>");
                                            print("</tr>");
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>