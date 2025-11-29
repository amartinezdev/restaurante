<?php
include("seguridad.php");

?>

<div class="row">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-top justify-content-end w-100">
        <!-- Botón hamburguesa -->
        <button class="navbar-toggler ms-auto me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenedor colapsable -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarMain">
            <ul class="navbar-nav mb-2 mb-lg-0 text-center">

                <!-- Productos (dropdown) -->
                <li class="nav-item dropdown mx-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center text-white"
                        href="#" id="dropdownProductos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box me-2" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z" />
                        </svg>
                        Productos
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-center" aria-labelledby="dropdownProductos">
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/encargado/encargado.php">Ver productos</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/encargado/addProductos.php">Añadir productos</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/encargado/categorias.php">Ver categorías</a></li>
                    </ul>
                </li>

                <!-- Estadísticas -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center text-white"
                        href="<?php echo BASE_PATH; ?>/encargado/estadisticas.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                            <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z" />
                        </svg>&nbsp;
                        Estadísticas
                    </a>
                </li>

                <!-- Personal (dropdown) -->
                <li class="nav-item dropdown mx-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center text-white"
                        href="#" id="dropdownPersonal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>&nbsp;
                        Personal
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-center" aria-labelledby="dropdownPersonal">
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/encargado/personal.php">Ver personal</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/encargado/addPersonal.php">Añadir personal</a></li>
                    </ul>
                </li>

                <!-- Perfil -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center text-white"
                        href="<?php echo BASE_PATH; ?>/encargado/perfil.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        Perfil
                    </a>
                </li>

                <!-- Cerrar sesión -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center text-light" href="<?php echo BASE_PATH; ?>/encargado/cerrarSesion.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-box-arrow-left me-2" viewBox="0 0 16 16" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                        </svg>
                        Cerrar sesión
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</div>