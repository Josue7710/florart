<section class="login-wrap"><form class="login-card" method="post" action="<?= e(url('login')) ?>"><?= csrf_field() ?><span class="login-flower">✿</span><span class="eyebrow">QUÉ BUENO VERTE</span><h1>Iniciar sesión</h1><p>Accede para revisar tus pedidos.</p>
<?php if (!empty($error)): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<label>Correo electrónico<input type="email" name="correo" required maxlength="190" value="<?= e($correo) ?>" placeholder="correo@ejemplo.com"></label>
<label>Contraseña<input type="password" name="password" required placeholder="Tu contraseña"></label>
<button class="btn full-btn" type="submit">Ingresar →</button>
<small>¿Aún no tienes cuenta? <a href="<?= e(url('register')) ?>">Regístrate</a></small></form></section>
