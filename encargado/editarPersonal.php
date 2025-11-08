<?php
session_start();

include("../components/conexion.php");

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}
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

        $idPersonal = $_GET["id"];
        $_SESSION["idPersonal"] = $idPersonal;

        $consulta = "SELECT * FROM usuario WHERE dni = '$idPersonal'";
        $result = mysqli_query($conn, $consulta);

        $row = mysqli_fetch_array($result);
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="text-center mb-3">
                            <h2 class="display-6">
                                <a href="/restaurante/encargado/personal.php" class="float-end link-light"> <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
                                    </svg></a>
                                Editando a <strong><?php echo $row["nombre"] ?></strong>
                            </h2>
                        </header>
                        <div class="row justify-content-center">
                            <form action="editarPersonal02.php" method="post" class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $row["nombre"] ?>" required />
                                    <label for="nombre">Nombre</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="apellido" value="<?php echo $row["apellido"] ?>" required />
                                    <label for="apellido">Apellido</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email" value="<?php echo $row["email"] ?>" required />
                                    <label for="email">Email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="telefono" value="<?php echo $row["telefono"] ?>" required />
                                    <label for="telefono">Teléfono</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <div class="mb-3">
                                        <label for="bloqueado" class="form-label">Bloqueado</label>
                                        <select
                                            class="form-select form-select-lg"
                                            name="bloqueado"
                                            id="bloqueado">

                                            <?php
                                            // si está bloqueado, que aparezca que NO de primeras
                                            if ($row["estado"] == 0) {
                                            ?>
                                                <option value="no" selected>No</option>
                                                <option value="si">Si</option>
                                            <?php
                                            } else {
                                                // si está desbloqueado, que aparezca que SI
                                            ?>
                                                <option value="si" selected>Si</option>
                                                <option value="no">No</option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <div class="mb-3">
                                        <label for="rol" class="form-label">Rol</label>
                                        <select
                                            class="form-select form-select-lg"
                                            name="rol"
                                            id="rol">
                                            <?php
                                            // si está bloqueado, que aparezca que NO de primeras
                                            if ($row["rol"] == 1) {
                                            ?>
                                                <option value="camarero" selected>Camarero</option>
                                                <option value="encargado">Encargado</option>
                                            <?php
                                            } else {
                                                // si está desbloqueado, que aparezca que SI
                                            ?>
                                                <option value="encargado">Encargado</option>
                                                <option value="camarero" selected>Camarero</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

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