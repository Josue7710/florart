<section class="page-intro"><span class="eyebrow">TU HISTORIAL</span><h1>Mis pedidos</h1><p>Sigue el estado de tus solicitudes.</p></section>
<section class="table-card">
<?php if (!$orders): ?>
  <p class="empty">Aún no tienes pedidos. <a href="<?= e(url('customize')) ?>">Personaliza tu primer arreglo</a>.</p>
<?php else: ?>
  <div class="table-scroll"><table><thead><tr><th>Código</th><th>Fecha</th><th>Arreglo</th><th>Colores</th><th>Tamaño</th><th>Estado</th></tr></thead><tbody>
  <?php foreach ($orders as $o): ?>
    <tr><td><strong><?= e($o['codigo']) ?></strong></td><td><?= e(date('d/m/Y', strtotime($o['creado_en']))) ?></td><td><?= e($o['nombre_producto'] ?: 'Diseño propio') ?> · <?= e($o['tipo_flor']) ?></td><td><?= e($o['colores']) ?></td><td><?= e($o['tamano']) ?></td><td><span class="status-pill <?= e(status_class($o['estado'])) ?>"><?= e($o['estado']) ?></span></td></tr>
  <?php endforeach; ?>
  </tbody></table></div>
<?php endif; ?>
</section>
