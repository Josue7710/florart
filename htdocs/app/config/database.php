<?php
// Credenciales de la base de datos.
// En XAMPP funcionan los valores por defecto de abajo.
// En el hosting NO edites este archivo: copia database.local.example.php
// como database.local.php y pon ahí los datos que te da el panel.
if (is_file(__DIR__ . '/database.local.php')) {
    require_once __DIR__ . '/database.local.php';
}
if (!defined('DB_HOST')) { define('DB_HOST', 'localhost'); }
if (!defined('DB_NAME')) { define('DB_NAME', 'florarte'); }
if (!defined('DB_USER')) { define('DB_USER', 'root'); }
if (!defined('DB_PASS')) { define('DB_PASS', ''); }

// Nombres de las tablas en la base de datos. Si las renombras en phpMyAdmin,
// cambia aquí (o en database.local.php) el nombre nuevo y el sitio seguirá funcionando.
if (!defined('T_USUARIOS'))  { define('T_USUARIOS',  'usuarios'); }
if (!defined('T_PRODUCTOS')) { define('T_PRODUCTOS', 'productos'); }
if (!defined('T_PEDIDOS'))   { define('T_PEDIDOS',   'pedidos'); }

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
