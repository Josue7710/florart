<?php $selectedCat = (string)($_GET['categoria'] ?? ''); ?>
<section class="page-intro"><span class="eyebrow">HECHOS PARA REGALAR</span><h1>Nuestro catálogo</h1><p>Encuentra una idea bonita y personalízala a tu gusto.</p></section>
<div class="catalog-toolbar"><input id="searchProducts" type="search" placeholder="Buscar arreglos..." aria-label="Buscar arreglos"><select id="categoryFilter" aria-label="Filtrar por categoría"><option value="">Todas las categorías</option><?php foreach (Product::CATEGORIES as $cat): ?><option <?= $selectedCat === $cat ? 'selected' : '' ?>><?= e($cat) ?></option><?php endforeach; ?></select></div>
<?php if (!$products): ?><p class="empty">Por ahora no hay productos disponibles.</p><?php endif; ?>
<p class="empty" id="noResults" hidden>No encontramos arreglos con esa búsqueda.</p>
<div class="product-grid">
<?php foreach ($products as $product): ?>
  <article class="product-card" data-nombre="<?= e(mb_strtolower($product['nombre'])) ?>" data-categoria="<?= e($product['categoria']) ?>">
    <div class="product-art"><img src="<?= e(product_image($product)) ?>" alt="<?= e($product['nombre']) ?>" loading="lazy"><small><?= e($product['categoria']) ?></small></div>
    <div class="product-info"><h3><?= e($product['nombre']) ?></h3><p><?= e($product['descripcion']) ?></p><div class="product-bottom"><strong>Desde <?= e(money($product['precio_base'])) ?></strong><a class="small-btn" href="<?= e(url('customize', ['product' => (int)$product['id']])) ?>">Personalizar</a></div></div>
  </article>
<?php endforeach; ?>
</div>
