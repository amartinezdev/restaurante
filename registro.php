<?php
session_start();
include("components/conexion.php");
require_once __DIR__ . "/components/demo.php";
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>El Quinto Pino - Registro</title>
    <link rel="shortcut icon" href="img/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <div class="container-fluid vh-100 auth-view">

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
                $mensaje = "El DNI $dni ya se encuentra registrado.";
            } else if (mysqli_num_rows($resultMail) == 1) {
                $mensaje = "El email $mail ya se encuentra registrado.";
            } else if (mysqli_num_rows($resultTel) == 1) {
                $mensaje = "El teléfono $tel ya se encuentra registrado.";
            } else if (DEMO_MODE && demo_cuentas_creadas($conn) >= DEMO_MAX_CUENTAS_NUEVAS) {
                $mensaje = "Se ha alcanzado el límite de " . DEMO_MAX_CUENTAS_NUEVAS . " cuentas nuevas por ciclo de demo. Podrás registrarte después del próximo reinicio (00:00h).";
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
                $_SESSION["rol"] = 0;
                header("LOCATION: cliente/mesa.php");
                exit;
            }
        }

        ?>

        <!-- HEADER -->
        <?php
        include("components/header.php");

        ?>

        <!-- SECTION -->
        <section class="row justify-content-center align-items-center py-4 py-sm-5" style="min-height: 75vh;">
            <!-- MAIN -->
            <main class="registro col-12 col-sm-8 col-md-7 col-lg-5 col-xl-4 col-xxl-4 rounded-4 p-4 p-sm-5">
                <div class="login-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="19" y1="8" x2="19" y2="14"></line>
                        <line x1="22" y1="11" x2="16" y2="11"></line>
                    </svg>
                </div>
                <h3 class="text-center mb-1">Crea tu cuenta</h3>
                <p class="text-center text-muted mb-4 small">Regístrate para poder hacer pedidos</p>

                <?php if (isset($mensaje)) { ?>
                    <div class="auth-alert mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="12" y1="8" x2="12" y2="12.5"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span><?php echo $mensaje; ?></span>
                    </div>
                <?php } ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dni" name="dni" maxlength="9" placeholder="DNI" value="<?php if (isset($_POST['dni'])) echo $_POST["dni"] ?>" required />
                        <label for="dni">DNI</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pw" name="pw" placeholder="Contraseña" value="<?php if (isset($_POST['pw'])) echo $_POST["pw"] ?>" required />
                        <label for="pw">Contraseña</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php if (isset($_POST['nombre'])) echo $_POST["nombre"] ?>" required />
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" value="<?php if (isset($_POST['apellido'])) echo $_POST["apellido"] ?>" required />
                        <label for="apellido">Apellido</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php if (isset($_POST['email'])) echo $_POST["email"] ?>" required />
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tel" name="tel" maxlength="9" placeholder="Teléfono" value="<?php if (isset($_POST['tel'])) echo $_POST["tel"] ?>" required />
                        <label for="tel">Teléfono</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" value="<?php if (isset($_POST['direccion'])) echo $_POST["direccion"] ?>" />
                        <label for="direccion">Dirección</label>
                    </div>
                    <div class="d-grid ">
                        <button type="submit" class="btn btn-success fw-bold d-flex align-items-center justify-content-center gap-2 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg>
                            Registrar</button>
                    </div>

                    <hr class="my-4 opacity-25" />

                    <div class="text-center small">
                        <span class="text-muted">¿Ya tienes cuenta?</span>
                        <a href="index.php" class="link-success fw-semibold text-decoration-none ms-1">Inicia sesión</a>
                    </div>
                </form>
            </main>
        </section>

        <video class="video-fondo" autoplay muted loop playsinline>
            <source src="img/fondo.mov" type="video/mp4" />
            Tu navegador no soporta videos HTML5.
        </video>
        <div class="video-overlay"></div>

        <!-- FOOTER -->
        <footer class="row">
            <div class="col-12"></div>
        </footer>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>