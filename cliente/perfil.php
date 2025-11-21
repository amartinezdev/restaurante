<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");
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
        $dni = $_SESSION["dni"];

        $consulta = "SELECT * FROM usuario WHERE dni = '$dni'";
        $result = mysqli_query($conn, $consulta);

        $row = mysqli_fetch_array($result);

        mysqli_close($conn);

        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="d-flex justify-content-between align-items-center">
                            <h2 class="display-6 m-0 text-center flex-grow-1">Editar perfil</h2>
                        </header>
                        <?php
                        if ($row["rol"] == 2) {
                            echo '<p class="text-muted text-center mb-3"><i>Encargado</i></p>';
                        } else if ($row["rol"] == 1) {
                            echo '<p class="text-muted text-center mb-3"><i>Camarero</i></p>';
                        } else {
                            echo '<p class="text-muted text-center mb-3"><i>Cliente</i></p>';
                        }

                        if (isset($_SESSION["mensaje"])) {
                            echo $_SESSION["mensaje"];
                        }
                        unset($_SESSION["mensaje"]); // Borrar el mensaje después de mostrarlo
                        ?>
                        <div class="row justify-content-center">
                            <form action="editarPerfil.php" method="post" class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="dni" name="dni" placeholder="dni" value="<?php echo $row["dni"] ?>" disabled required />
                                    <label for="dni">DNI</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $row["nombre"] ?>" required />
                                    <label for="nombre">Nombre *</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="apellido" value="<?php echo $row["apellido"] ?>" required />
                                    <label for="apellido">Apellido *</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="pw" name="pw" placeholder="pw" value="<?php echo $row["password"] ?>" required />
                                    <label for="pw">Contraseña *</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email" value="<?php echo $row["email"] ?>" required />
                                    <label for="email">Email *</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="telefono" value="<?php echo $row["telefono"] ?>" required />
                                    <label for="telefono">Teléfono *</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="direccion" value="<?php echo $row["direccion"] ?>" />
                                    <label for="direccion">Dirección</label>
                                </div>

                                <div class="d-grid ">
                                    <button type="submit" class="btn btn-light fw-bold lead d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                                            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                                        </svg>
                                        &nbsp; Editar</button>
                                </div>
                            </form>
                        </div>

                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>