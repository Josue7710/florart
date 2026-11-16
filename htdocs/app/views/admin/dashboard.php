<section class="page-intro"><span class="eyebrow">ÁREA PRIVADA</span><h1>Panel de administración</h1><p>Organiza productos y solicitudes de tus clientes.</p></section>
<div class="stats-grid">
  <div class="stat-card"><span>Pedidos recibidos</span><strong><?= (int)$stats['total'] ?></strong><small>Total histórico</small></div>
  <div class="stat-card"><span>Por revisar</span><strong><?= (int)$stats['pending'] ?></strong><small>Pendientes y en revisión</small></div>
  <div class="stat-card"><span>Productos activos</span><strong><?= (int)$stats['products'] ?></strong><small>Visibles en el catálogo</small></div>
</div>
<section class="table-card">
  <div class="table-heading"><h2>Pedidos recientes</h2><a class="text-link" href="<?= e(url('admin-orders')) ?>">Ver todos →</a></div>
  <?php if (!$recent): ?><p class="empty">Todavía no hay pedidos.</p><?php else: ?>
  <div class="table-scroll"><table><thead><tr><th>Código</th><th>Cliente</th><th>Fecha</th><th>Estado</th></tr></thead><tbody>
  <?php foreach ($recent as $o): ?>
    <tr><td><?= e($o['codigo']) ?></td><td><?= e($o['nombre_cliente']) ?></td><td><?= e(date('d/m/Y', strtotime($o['creado_en']))) ?></td><td><span class="status-pill <?= e(status_class($o['estado'])) ?>"><?= e($o['estado']) ?></span></td></tr>
  <?php endforeach; ?></tbody></table></div>
  <?php endif; ?>
</section>
