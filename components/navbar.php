<?php
// Defaults si no vienen desde la página que hace el include
$navbar1 = $navbar1 ?? '#';
$navbar2 = $navbar2 ?? '#';
$navbar3 = $navbar3 ?? '#';

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
                <li class="nav-item mx-3">
                    <a class="nav-link <?php echo $active === 'mesas' ? 'text-secondary' : 'text-light' ?>"
                        href="<?php echo $navbar1 ?>">
                        <i class="bi bi-house"></i> Mesas
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link <?php echo $active === 'pedidos' ? 'text-secondary' : 'text-light' ?>"
                        href="<?php echo $navbar2 ?>">
                        <i class="bi bi-grid"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link <?php echo $active === 'perfil' ? 'text-secondary' : 'text-light' ?>"
                        href="<?php echo $navbar3 ?>">
                        <i class="bi bi-person"></i> Perfil
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link text-light" href="cerrarSesion.php">
                        <i class="bi bi-box-arrow-left"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>