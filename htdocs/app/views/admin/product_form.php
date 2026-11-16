<section class="page-intro"><span class="eyebrow">ÁREA PRIVADA</span><h1><?= $product['id'] ? 'Editar producto' : 'Nuevo producto' ?></h1></section>
<?php foreach ($errors as $err): ?><div class="notice error"><?= e($err) ?></div><?php endforeach; ?>
<form class="form-card" method="post" action="<?= e(url('admin-product-save')) ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
  <div class="form-grid">
    <label class="full">Nombre *<input name="nombre" required maxlength="120" value="<?= e($product['nombre']) ?>"></label>
    <label class="full">Descripción *<input name="descripcion" required maxlength="255" value="<?= e($product['descripcion']) ?>"></label>
    <label>Precio base (Bs) *<input name="precio_base" type="number" min="0" step="0.01" required value="<?= e($product['precio_base']) ?>"></label>
    <label>Categoría<select name="categoria"><?php foreach (Product::CATEGORIES as $c): ?><option <?= $product['categoria'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select></label>
    <label>Tipo de flor<select name="tipo_flor"><?php foreach (Product::FLOWER_TYPES as $t): ?><option <?= $product['tipo_flor'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select></label>
    <label class="check"><input type="checkbox" name="activo" value="1" <?= !empty($product['activo']) ? 'checked' : '' ?>> Visible en el catálogo</label>
    <div class="full image-field">
      <img class="image-preview" src="<?= e(product_image($product)) ?>" alt="Imagen actual del producto">
      <div>
        <label>Imagen del producto (JPG, PNG o WEBP, máx. 2 MB)<input type="file" name="imagen" accept="image/jpeg,image/png,image/webp"></label>
        <?php if (!empty($product['imagen'])): ?><label class="check"><input type="checkbox" name="remove_image" value="1"> Quitar la imagen actual (usar la ilustración por defecto)</label><?php endif; ?>
        <small class="muted">Si no subes ninguna, se usa una ilustración según la categoría.</small>
      </div>
    </div>
  </div>
  <div class="form-bottom"><a class="text-link" href="<?= e(url('admin-products')) ?>">← Cancelar</a><button class="btn" type="submit">Guardar <span>→</span></button></div>
</form>
