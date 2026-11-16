<section class="page-intro"><span class="eyebrow">ÁREA PRIVADA</span><h1>Productos</h1><p>Administra lo que se muestra en el catálogo.</p></section>
<section class="table-card">
  <div class="table-heading"><h2>Catálogo (<?= count($products) ?>)</h2><a class="btn btn-small" href="<?= e(url('admin-product-new')) ?>">+ Nuevo producto</a></div>
  <?php if (!$products): ?><p class="empty">No hay productos todavía.</p><?php else: ?>
  <div class="table-scroll"><table><thead><tr><th>Imagen</th><th>Nombre</th><th>Categoría</th><th>Tipo</th><th>Precio base</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><img class="thumb" src="<?= e(product_image($p)) ?>" alt=""></td>
      <td><strong><?= e($p['nombre']) ?></strong><br><small class="muted"><?= e($p['descripcion']) ?></small></td>
      <td><?= e($p['categoria']) ?></td><td><?= e($p['tipo_flor']) ?></td><td><?= e(money($p['precio_base'])) ?></td>
      <td><span class="status-pill <?= $p['activo'] ? 'done' : '' ?>"><?= $p['activo'] ? 'Activo' : 'Oculto' ?></span></td>
      <td class="row-actions">
        <a class="small-btn" href="<?= e(url('admin-product-edit', ['id' => (int)$p['id']])) ?>">Editar</a>
        <form method="post" action="<?= e(url('admin-product-toggle')) ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="small-btn" type="submit"><?= $p['activo'] ? 'Ocultar' : 'Mostrar' ?></button></form>
        <form method="post" action="<?= e(url('admin-product-delete')) ?>" onsubmit="return confirm('¿Eliminar este producto? Esta acción no se puede deshacer.');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="small-btn danger" type="submit">Eliminar</button></form>
      </td>
    </tr>
  <?php endforeach; ?></tbody></table></div>
  <?php endif; ?>
</section>
