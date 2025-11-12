<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");

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
                            <h4 class="display-6">Listado del personal</h4>
                        </header>
                        <section class="row justify-content-center ">
                            <div class="col-12 table-responsive">
                                <table class="table text-start text-md-center table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class='d-none d-lg-table-cell'>DNI</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Rol</th>
                                            <th class='d-none d-md-table-cell'>Email</th>
                                            <th colspan="2">Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // realizo una tabla con todos los usuarios de la base de datos.
                                        $consulta = "SELECT * FROM usuario WHERE rol = 2 OR rol = 1";

                                        $result = mysqli_query($conn, $consulta);

                                        while ($row = mysqli_fetch_array($result)) {
                                            $id = $row['dni'];
                                            $estado = $row["estado"];

                                            // listamos todos menos el que está consultando actualmente
                                            if ($usuario != $row["nombre"]) {
                                                if ($estado == 0) {
                                                    print("<tr>");
                                                } else {
                                                    print("<tr class='table-danger'>");
                                                }
                                                print("<td class='d-none d-lg-table-cell'>");
                                                print($row['dni']);
                                                print("</td>");

                                                print("<td>");
                                                print($row['nombre']);
                                                print("</td>");

                                                print("<td>");
                                                print($row['apellido']);
                                                print("</td>");

                                                print("<td>");
                                                if ($row['rol'] == 2) {
                                                    print("Encargado");
                                                } else if ($row['rol'] == 1) {
                                                    print("Camarero");
                                                }
                                                print("</td>");

                                                print("<td class='d-none d-md-table-cell'>");
                                                print($row['email']);
                                                print("</td>");

                                                print("<td>");
                                                print("<a href='editarPersonal.php?id=$id' class='btn btn-primary'>Editar</a>");
                                                print("</td>");
                                                print("<td class='d-none d-md-table-cell'>");
                                                if ($estado == 0) {
                                                    print("<a href='bloquearPersonal.php?id=$id' class='btn btn-danger'>Bloquear</a>");
                                                } else {
                                                    print("<a href='desbloquearPersonal.php?id=$id' class='btn btn-success'>Desloquear</a>");
                                                }

                                                print("</td>");
                                                print("</tr>");
                                            }
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