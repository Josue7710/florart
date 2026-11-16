<section class="page-intro"><span class="eyebrow">TU IDEA, A TU ESTILO</span><h1>Personaliza tu arreglo</h1><p>Cuéntanos cómo te gustaría que fuera tu detalle.</p></section>
<?php if (!empty($message)): ?><div class="notice error"><?= e($message) ?></div><?php endif; ?>
<form class="form-card" method="post" action="<?= e(url('save-order')) ?>">
  <?= csrf_field() ?>
  <div class="form-section-title"><span>01</span><div><h2>Sobre tu arreglo</h2><p>Elige los detalles principales.</p></div></div>
  <div class="form-grid">
    <label class="full">Basado en (opcional)
      <select name="producto_id">
        <option value="">Diseño propio</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= (int)$p['id'] ?>" <?= (string)$form['producto_id'] === (string)$p['id'] ? 'selected' : '' ?>><?= e($p['nombre']) ?> — desde <?= e(money($p['precio_base'])) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Tipo de flor<select name="tipo_flor"><?php foreach (Product::FLOWER_TYPES as $t): ?><option <?= $form['tipo_flor'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></select></label>
    <label>Tamaño<select name="tamano"><?php foreach (Order::SIZES as $s): ?><option <?= $form['tamano'] === $s ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select></label>
    <label class="full">Colores preferidos *<input name="colores" required maxlength="255" value="<?= e($form['colores']) ?>" placeholder="Ej. rosa pastel, blanco y verde"></label>
    <label>Tipo de envoltura<input name="envoltura" maxlength="150" value="<?= e($form['envoltura']) ?>" placeholder="Ej. papel kraft, elegante..."></label>
    <label>Presupuesto aproximado (Bs)<input name="presupuesto" type="number" min="0" step="0.5" value="<?= e($form['presupuesto']) ?>" placeholder="Ej. 150"></label>
    <label class="full">Dedicatoria<textarea name="dedicatoria" rows="3" maxlength="1000" placeholder="Escribe tu mensaje especial..."><?= e($form['dedicatoria']) ?></textarea></label>
  </div>
  <div class="form-section-title"><span>02</span><div><h2>Tus datos</h2><p>Para que podamos identificar tu solicitud.</p></div></div>
  <div class="form-grid">
    <label>Nombre completo *<input name="nombre_cliente" required maxlength="120" value="<?= e($form['nombre_cliente']) ?>" placeholder="Tu nombre"></label>
    <label>Correo electrónico *<input name="correo" type="email" required maxlength="190" value="<?= e($form['correo']) ?>" placeholder="correo@ejemplo.com"></label>
  </div>
  <div class="form-bottom"><p>El presupuesto es estimado. El precio final debe confirmarlo el emprendedor.<?php if (!Auth::check()): ?> <a href="<?= e(url('login')) ?>">Inicia sesión</a> para ver el estado de tus pedidos.<?php endif; ?></p><button class="btn" type="submit">Enviar solicitud <span>→</span></button></div>
</form>
