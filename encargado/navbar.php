<?php
// ************* NAVBAR DE ENCARGADO *************
$active  = $active  ?? '';
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
                <!-- Ver productos -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center <?php echo $active === 'productos' ? 'text-secondary' : 'text-light' ?>"
                        href="/restaurante/encargado/encargado.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-house me-2" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                        </svg>
                        Ver productos
                    </a>
                </li>

                <!-- Añadir productos -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center <?php echo $active === 'addproductos' ? 'text-secondary' : 'text-light' ?>"
                        href="/restaurante/encargado/addProductos.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-house me-2" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                        </svg>
                        Añadir productos
                    </a>
                </li>

                <!-- Categorías -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center <?php echo $active === 'perfil' ? 'text-secondary' : 'text-light' ?>"
                        href="/restaurante/encargado/perfil.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        Categorías
                    </a>
                </li>

                <!-- Personal -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center <?php echo $active === 'personal' ? 'text-secondary' : 'text-light' ?>"
                        href="/restaurante/encargado/personal.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                        &nbsp;&nbsp;Personal
                    </a>
                </li>

                <!-- Perfil -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center <?php echo $active === 'perfil' ? 'text-secondary' : 'text-light' ?>"
                        href="/restaurante/encargado/perfil.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        Perfil
                    </a>
                </li>

                <!-- Cerrar sesión -->
                <li class="nav-item mx-3">
                    <a class="nav-link d-flex align-items-center justify-content-center text-light" href="cerrarSesion.php">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-box-arrow-left me-2" viewBox="0 0 16 16">
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