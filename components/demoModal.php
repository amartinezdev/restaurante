<?php
// Modal compartida para las acciones bloqueadas en modo demo. Se incluye una
// sola vez desde components/header.php. Lee $_SESSION['demo_block'] (lo deja
// alguna de las páginas de acción cuando DEMO_MODE bloquea algo), lo consume
// y, si hay motivo, la pinta ya abierta.
$demoBlockMotivo = $_SESSION['demo_block'] ?? null;
unset($_SESSION['demo_block']);

$demoBlockMensajes = [
    'password' => 'Por motivos de demostración no se puede cambiar la contraseña de los usuarios de prueba. El resto de cambios de tu perfil sí se han guardado.',
    'limite_productos' => 'Se ha alcanzado el límite de ' . DEMO_MAX_PRODUCTOS_NUEVOS . ' productos nuevos por ciclo de demo. Podrás crear más después del próximo reinicio (00:00h).',
    'limite_cuentas' => 'Se ha alcanzado el límite de ' . DEMO_MAX_CUENTAS_NUEVAS . ' cuentas nuevas por ciclo de demo. Podrás crear más después del próximo reinicio (00:00h).',
    'limite_categorias' => 'Se ha alcanzado el límite de ' . DEMO_MAX_CATEGORIAS_NUEVAS . ' categorías nuevas por ciclo de demo. Podrás crear más después del próximo reinicio (00:00h).',
];
?>

<div class="modal fade" id="demoBlockModal" tabindex="-1" aria-labelledby="demoBlockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="demoBlockModalLabel">Acción no disponible en la demo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="demoBlockModalMensaje"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Entendido</button>
      </div>
    </div>
  </div>
</div>

<?php if ($demoBlockMotivo && isset($demoBlockMensajes[$demoBlockMotivo])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('demoBlockModalMensaje').textContent = <?php echo json_encode($demoBlockMensajes[$demoBlockMotivo]); ?>;
    new bootstrap.Modal(document.getElementById('demoBlockModal')).show();
  });
</script>
<?php endif; ?>
