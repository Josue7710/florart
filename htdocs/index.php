

<?php
// Punto de entrada único de FlorArte (enrutador MVC).
require_once __DIR__ . '/app/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', $https ? '1' : '0');
session_start();

require_once __DIR__ . '/app/core/helpers.php';

// Carga automática de clases: core, models y controllers.
spl_autoload_register(function ($class) {
    foreach (['core', 'models', 'controllers'] as $dir) {
        $file = __DIR__ . "/app/$dir/$class.php";
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

(new Router())->dispatch();

