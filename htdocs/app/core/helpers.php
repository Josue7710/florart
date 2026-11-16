<?php
// Compatibilidad si el hosting no tiene la extensión mbstring.
if (!function_exists('mb_strlen')) {
    function mb_strlen($s) { return preg_match_all('/./su', (string)$s); }
}
if (!function_exists('mb_strtolower')) {
    function mb_strtolower($s) { return strtolower((string)$s); }
}

/** Escapa texto para imprimirlo de forma segura en HTML. */
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function money($value): string {
    return 'Bs ' . number_format((float)$value, 2);
}

/** URL interna: url('catalog', ['categoria' => 'Ramos']). Usa siempre index.php. */
function url(string $page = 'home', array $params = []): string {
    $script = $_SERVER['SCRIPT_NAME'] ?? 'index.php';
    return $script . '?' . http_build_query(['page' => $page] + $params);
}

/** Token CSRF de la sesión actual. */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(): bool {
    $sent = $_POST['csrf'] ?? '';
    return is_string($sent) && $sent !== '' && hash_equals($_SESSION['csrf'] ?? '', $sent);
}

/** Clase CSS según el estado del pedido. */
function status_class(string $status): string {
    $map = [
        'Pendiente'   => 'pending',
        'En revisión' => 'review',
        'Preparando'  => 'review',
        'Confirmado'  => 'done',
        'Entregado'   => 'done',
        'Cancelado'   => 'cancelled',
    ];
    return $map[$status] ?? '';
}

/** Ruta de la imagen de un producto (subida o ilustración por defecto). */
function product_image(array $p): string {
    $img = (string)($p['imagen'] ?? '');
    if ($img !== '' && preg_match('#^(assets/img/products|uploads)/[A-Za-z0-9._-]+$#', $img)) {
        return $img;
    }
    $byCategory = [
        'Ramos' => 'ramo-primavera',
        'Flores eternas' => 'flores-eternas',
        'Regalos' => 'caja-regalo',
    ];
    return 'assets/img/products/' . ($byCategory[$p['categoria'] ?? ''] ?? 'default') . '.svg';
}
