# FlorArte

Plataforma web para explorar y pedir arreglos florales personalizados (PHP 7.4+ y MySQL, patrón MVC).

## Qué incluye
- Catálogo con imágenes, búsqueda y filtro por categoría.
- Formulario de personalización de pedidos (con código de referencia).
- Registro e inicio de sesión (clientes y administrador), "Mis pedidos".
- Panel de administración: productos (crear, editar, ocultar, eliminar, subir imagen) y pedidos (cambiar estado).
- Seguridad: contraseñas con `password_hash`, consultas preparadas (PDO), token CSRF, escape de salida,
  permisos por rol, bloqueo tras 5 intentos fallidos de login y subida de imágenes validada.

## Estructura
```
index.php          punto de entrada
app/               modelos, vistas, controladores, núcleo y configuración (no accesible desde el navegador)
assets/            CSS, JS e imágenes
uploads/           imágenes que sube el administrador
database/          florarte.sql (tablas y datos de ejemplo) y migracion_columnas_es.sql
```

## Base de datos
Tablas: `usuarios`, `productos` y `pedidos`. Las columnas están en español (por ejemplo `nombre`, `correo`,
`contrasena_hash`, `rol`, `precio_base`, `tipo_flor`, `nombre_cliente`, `tamano`, `estado`, `creado_en`).
Si ya importaste una versión anterior con columnas en inglés y quieres conservar tus datos, ejecuta
`database/migracion_columnas_es.sql` en la pestaña SQL de phpMyAdmin (una sola vez).

## Probar en XAMPP
1. Copia la carpeta en `C:\xampp\htdocs\florarte`.
2. En phpMyAdmin crea una base llamada `florarte` e importa `database/florarte.sql`.
3. Abre `http://localhost/florarte/`.

## Publicar en ByetHost
1. En el panel, crea una base en **MySQL Databases** y anota host, nombre, usuario y contraseña.
2. En phpMyAdmin selecciona esa base e importa `database/florarte.sql`.
3. En `app/config/` copia `database.local.example.php` como `database.local.php` y escribe esos datos.
4. Sube **el contenido** de esta carpeta (no la carpeta en sí) a `htdocs/` por FTP o por el administrador de archivos.
   La carpeta `uploads/` debe poder escribirse (permisos 755 o 775).
5. Abre tu dominio y prueba el catálogo, el registro, un pedido y el acceso de administrador.

## Usuarios de prueba
- Cliente: `cliente@florarte.com` / `Cliente123!`
- Administrador: `admin@florarte.com` (la contraseña se entrega aparte, no está en el repositorio).

## Imágenes
Las ilustraciones de `assets/img/` son de uso libre en este proyecto. Desde el panel de administración
se pueden reemplazar por fotos reales (JPG, PNG o WEBP de hasta 2 MB).
