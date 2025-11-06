<?php
session_start();
include("components/conexion.php");

?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>El Quinto Pino</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <div class="container-fluid vh-100">

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $dni = $_POST["dni"];
            $pw = $_POST["pw"];
            $nombre = $_POST["nombre"];
            $ape = $_POST["apellido"];
            $mail = $_POST["email"];
            $tel = $_POST["tel"];
            $dir = $_POST["direccion"];

            $consultaDni = "SELECT dni FROM usuario WHERE dni = '$dni'";
            $consultaMail = "SELECT email FROM usuario WHERE email = '$mail'";
            $consultaTel = "SELECT telefono FROM usuario WHERE telefono = '$tel'";

            $resultDni = mysqli_query($conn, $consultaDni);
            $resultMail = mysqli_query($conn, $consultaMail);
            $resultTel = mysqli_query($conn, $consultaTel);

            if (mysqli_num_rows($resultDni) == 1) {
                $mensaje = "<p class='text-warning'>El DNI $dni ya se encuentra registrado.</p>";
            } else if (mysqli_num_rows($resultMail) == 1) {
                $mensaje = "<p class='text-warning'>El email $mail ya se encuentra registrado.</p>";
            } else if (mysqli_num_rows($resultTel) == 1) {
                $mensaje = "<p class='text-warning'>El teléfono $tel ya se encuentra registrado.</p>";
            } else {

                if (isset($dir)) {
                    $consulta = "INSERT INTO usuario 
                    (dni, password, nombre, apellido, rol, email, telefono, direccion, estado) 
                    VALUES('$dni', '$pw', '$nombre', '$ape', '0','$mail', '$tel', '$dir', '0')";
                } else {
                    $consulta = "INSERT INTO usuario
                            (dni, password, nombre, apellido, rol, email, telefono, estado) 
                    VALUES('$dni', '$pw', '$nombre', '$ape', '0', '$mail', '$tel', '0')";
                }

                $result = mysqli_query($conn, $consulta);

                $_SESSION["usuario"] = $_POST["nombre"];
                $_SESSION["dni"] = $_POST["dni"];
                header("LOCATION: cliente/cliente.php");
                exit;
            }
        }

        ?>

        <!-- HEADER -->
        <?php
        include("components/header.php");

        ?>

        <!-- SECTION -->
        <section class="row justify-content-center align-items-center h-75">
            <!-- MAIN -->
            <main class="registro col-12 col-sm-8 col-md-7 col-lg-5 col-xl-4 col-xxl-4 bg-success p-4 rounded text-center mt-5">
                <h3 class="text-center text-white mb-4">Registro</h3>

                <?php
                if (isset($mensaje)) {
                    echo $mensaje;
                }

                ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dni" name="dni" placeholder="Usuario" required />
                        <label for="dni">DNI</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pw" name="pw" placeholder="Usuario" required />
                        <label for="pw">Contraseña</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Usuario" required />
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Usuario" required />
                        <label for="apellido">Apellido</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Usuario" required />
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Usuario" required />
                        <label for="tel">Teléfono</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Usuario" />
                        <label for="direccion">Dirección</label>
                    </div>
                    <div class="d-grid ">
                        <button type="submit" class="btn btn-light fw-bold lead d-flex align-items-center justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg>
                            &nbsp;Registrar</button>
                    </div>

                </form>
            </main>
        </section>

        <video class="video-fondo" autoplay muted loop>
            <source src="img/fondo.mov" type="video/mp4" />
            Tu navegador no soporta videos HTML5.
        </video>

        <!-- FOOTER -->
        <footer class="row">
            <div class="col-12"></div>
        </footer>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>