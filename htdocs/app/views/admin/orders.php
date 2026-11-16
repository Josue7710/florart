<section class="page-intro"><span class="eyebrow">ÁREA PRIVADA</span><h1>Pedidos</h1><p>Revisa las solicitudes y actualiza su estado.</p></section>
<section class="table-card">
  <div class="table-heading"><h2><?= count($orders) ?> pedido(s)</h2>
    <form method="get" class="inline-filter"><input type="hidden" name="page" value="admin-orders">
      <select name="estado" onchange="this.form.submit()"><option value="">Todos los estados</option><?php foreach (Order::STATUSES as $s): ?><option <?= $filter === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
    </form>
  </div>
  <?php if (!$orders): ?><p class="empty">No hay pedidos para mostrar.</p><?php else: ?>
  <div class="table-scroll"><table><thead><tr><th>Código</th><th>Cliente</th><th>Detalle</th><th>Presupuesto</th><th>Fecha</th><th>Estado</th></tr></thead><tbody>
  <?php foreach ($orders as $o): ?>
    <tr>
      <td><strong><?= e($o['codigo']) ?></strong></td>
      <td><?= e($o['nombre_cliente']) ?><br><small class="muted"><?= e($o['correo']) ?></small></td>
      <td><?= e($o['nombre_producto'] ?: 'Diseño propio') ?> · <?= e($o['tipo_flor']) ?> · <?= e($o['tamano']) ?><br><small class="muted">Colores: <?= e($o['colores']) ?><?= $o['envoltura'] ? ' · Envoltura: ' . e($o['envoltura']) : '' ?></small><?php if ($o['dedicatoria']): ?><br><small class="muted">“<?= e($o['dedicatoria']) ?>”</small><?php endif; ?></td>
      <td><?= $o['presupuesto'] !== null ? e(money($o['presupuesto'])) : '—' ?></td>
      <td><?= e(date('d/m/Y', strtotime($o['creado_en']))) ?></td>
      <td><form method="post" action="<?= e(url('admin-order-status')) ?>" class="status-form"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$o['id'] ?>"><input type="hidden" name="filter" value="<?= e($filter) ?>">
        <select name="estado"><?php foreach (Order::STATUSES as $s): ?><option <?= $o['estado'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select><button class="small-btn" type="submit">Guardar</button></form></td>
    </tr>
  <?php endforeach; ?></tbody></table></div>
  <?php endif; ?>
</section>
