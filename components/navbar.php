<?php
// Defaults si no vienen desde la página que hace el include
$navbar1 = $navbar1 ?? '#';
$navbar2 = $navbar2 ?? '#';
$navbar3 = $navbar3 ?? '#';

// Página activa opcional para estilos (por ejemplo: 'mesas', 'pedidos', 'perfil')
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
                    <a class="nav-link <?= $active === 'mesas' ? 'text-secondary' : 'text-light' ?>"
                        href="<?= htmlspecialchars($navbar1, ENT_QUOTES) ?>">
                        <i class="bi bi-house"></i> Mesas
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link <?= $active === 'pedidos' ? 'text-secondary' : 'text-light' ?>"
                        href="<?= htmlspecialchars($navbar2, ENT_QUOTES) ?>">
                        <i class="bi bi-grid"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link <?= $active === 'perfil' ? 'text-secondary' : 'text-light' ?>"
                        href="<?= htmlspecialchars($navbar3, ENT_QUOTES) ?>">
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