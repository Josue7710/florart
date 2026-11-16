<section class="login-wrap"><form class="login-card" method="post" action="<?= e(url('register')) ?>"><?= csrf_field() ?><span class="login-flower">❀</span><span class="eyebrow">BIENVENIDO/A</span><h1>Crear cuenta</h1><p>Guarda tus pedidos y sigue su estado.</p>
<?php foreach ($errors as $err): ?><div class="notice error"><?= e($err) ?></div><?php endforeach; ?>
<label>Nombre completo<input name="nombre" required maxlength="120" value="<?= e($old['nombre']) ?>" placeholder="Tu nombre"></label>
<label>Correo electrónico<input type="email" name="correo" required maxlength="190" value="<?= e($old['correo']) ?>" placeholder="correo@ejemplo.com"></label>
<label>Contraseña (mínimo 8 caracteres)<input type="password" name="password" required minlength="8" placeholder="Tu contraseña"></label>
<label>Confirmar contraseña<input type="password" name="password_confirm" required minlength="8" placeholder="Repite tu contraseña"></label>
<button class="btn full-btn" type="submit">Crear cuenta →</button>
<small>¿Ya tienes cuenta? <a href="<?= e(url('login')) ?>">Inicia sesión</a></small></form></section>
