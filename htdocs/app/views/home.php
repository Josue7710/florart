<section class="hero">
  <div class="hero-copy">
    <span class="eyebrow">FLORES CON SENTIMIENTO</span>
    <h1>Un detalle hecho<br>a tu manera</h1>
    <p>Personaliza arreglos florales naturales o sintéticos para cada ocasión especial.</p>
    <a class="btn" href="<?= e(url('catalog')) ?>">Explorar catálogo <span>→</span></a>
  </div>
  <div class="hero-art"><img src="assets/img/hero.svg" alt="Ramo de flores con lazo rosa" width="640" height="480"></div>
</section>

<section class="categories">
  <div class="section-heading"><div><span class="eyebrow">ENCUENTRA TU ESTILO</span><h2>Detalles que enamoran</h2></div><a class="text-link" href="<?= e(url('catalog')) ?>">Ver todo →</a></div>
  <div class="category-grid">
    <a class="category-card photo" href="<?= e(url('catalog', ['categoria' => 'Ramos'])) ?>"><img src="assets/img/products/ramo-primavera.svg" alt="" loading="lazy"><div><strong>Ramos</strong><small>Clásicos y coloridos</small></div></a>
    <a class="category-card photo" href="<?= e(url('catalog', ['categoria' => 'Flores eternas'])) ?>"><img src="assets/img/products/flores-eternas.svg" alt="" loading="lazy"><div><strong>Flores eternas</strong><small>Para guardar siempre</small></div></a>
    <a class="category-card photo" href="<?= e(url('catalog', ['categoria' => 'Regalos'])) ?>"><img src="assets/img/products/caja-regalo.svg" alt="" loading="lazy"><div><strong>Regalos</strong><small>Una sorpresa especial</small></div></a>
  </div>
</section>

<?php if (!empty($featured)): ?>
<section class="featured">
  <div class="section-heading"><div><span class="eyebrow">LOS FAVORITOS</span><h2>Arreglos destacados</h2></div><a class="text-link" href="<?= e(url('catalog')) ?>">Ver catálogo →</a></div>
  <div class="product-grid">
  <?php foreach ($featured as $product): ?>
    <article class="product-card">
      <div class="product-art"><img src="<?= e(product_image($product)) ?>" alt="<?= e($product['nombre']) ?>" loading="lazy"><small><?= e($product['categoria']) ?></small></div>
      <div class="product-info"><h3><?= e($product['nombre']) ?></h3><p><?= e($product['descripcion']) ?></p><div class="product-bottom"><strong>Desde <?= e(money($product['precio_base'])) ?></strong><a class="small-btn" href="<?= e(url('customize', ['product' => (int)$product['id']])) ?>">Personalizar</a></div></div>
    </article>
  <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="occasions">
  <div class="section-heading centered"><div><span class="eyebrow">PARA CADA MOMENTO</span><h2>Ocasiones especiales</h2></div></div>
  <div class="occasion-grid">
    <a class="occasion-card" href="<?= e(url('customize')) ?>"><img src="assets/img/ocasion-cumpleanos.svg" alt="Ramo colorido para cumpleaños" loading="lazy"><span>Cumpleaños</span></a>
    <a class="occasion-card" href="<?= e(url('customize')) ?>"><img src="assets/img/ocasion-aniversario.svg" alt="Ramo de rosas para aniversario" loading="lazy"><span>Aniversarios</span></a>
    <a class="occasion-card" href="<?= e(url('customize')) ?>"><img src="assets/img/ocasion-graduacion.svg" alt="Ramo azul para graduación" loading="lazy"><span>Graduaciones</span></a>
    <a class="occasion-card" href="<?= e(url('customize')) ?>"><img src="assets/img/ocasion-gracias.svg" alt="Ramo suave para agradecer" loading="lazy"><span>Agradecimientos</span></a>
  </div>
</section>

<section class="how">
  <div class="section-heading centered"><div><span class="eyebrow">FÁCIL Y PERSONAL</span><h2>¿Cómo funciona?</h2></div></div>
  <div class="steps">
    <article class="step"><span class="step-number">1</span><div><h3>Elige</h3><p>Explora nuestro catálogo y encuentra tu arreglo favorito.</p></div></article>
    <article class="step"><span class="step-number">2</span><div><h3>Personaliza</h3><p>Selecciona colores, tamaño, envoltura y dedicatoria.</p></div></article>
    <article class="step"><span class="step-number">3</span><div><h3>Envía tu pedido</h3><p>Revisa los detalles y envía tu solicitud.</p></div></article>
  </div>
</section>
