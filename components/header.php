<?php require_once __DIR__ . '/demo.php'; ?>

<header class="row text-center align-items-center p-2"
  style="background-color: var(--bs-body-bg); color: var(--bs-body-color);">
  <div class="col-12 d-flex justify-content-center align-items-center">
    <img src="/img/logo_white.png" alt="logo" class="me-3" width="100px">
    <h1>
      <span class="text-success">El</span> Quinto <span class="text-success">Pino</span>
    </h1>
  </div>
</header>

<?php if (DEMO_MODE): ?>
  <div class="demo-banner text-center small">
    🎭 Esto es una <strong>demo pública</strong> de El Quinto Pino. Los datos se reinician automáticamente
    cada día a las <strong>00:00h (hora de España)</strong>. No se pueden cambiar contraseñas de los
    usuarios de prueba, y hay un límite de productos y cuentas nuevas por ciclo.
  </div>
  <?php require_once __DIR__ . '/demoModal.php'; ?>
<?php endif; ?>