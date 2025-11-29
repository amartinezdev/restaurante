<?php require_once __DIR__ . '/demo.php'; ?>

<header class="row text-center align-items-center p-2"
  style="background-color: var(--bs-body-bg); color: var(--bs-body-color);">
  <div class="col-12 d-flex justify-content-center align-items-center">
    <img src="<?php echo BASE_PATH; ?>/img/logo_white.png" alt="logo" class="me-3" width="100px">
    <h1>
      <span class="text-success">El</span> Quinto <span class="text-success">Pino</span>
    </h1>
  </div>
</header>

<?php if (DEMO_MODE): ?>
  <div class="demo-banner text-center small d-flex align-items-center justify-content-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" class="flex-shrink-0">
      <circle cx="12" cy="12" r="9"></circle>
      <line x1="12" y1="10.5" x2="12" y2="16"></line>
      <line x1="12" y1="7.5" x2="12.01" y2="7.5"></line>
    </svg>
    <span>Esto es una <strong>demo pública</strong> de El Quinto Pino. Los datos se reinician automáticamente cada día a las <strong>00:00h (hora de España)</strong>.</span>
  </div>
  <?php require_once __DIR__ . '/demoModal.php'; ?>
<?php endif; ?>