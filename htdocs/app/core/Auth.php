<?php
/** Manejo de sesión y permisos por rol (admin / cliente). */
class Auth {
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }
    public static function check(): bool {
        return self::user() !== null;
    }
    public static function isAdmin(): bool {
        return (self::user()['rol'] ?? '') === 'admin';
    }
    public static function id(): ?int {
        return self::user()['id'] ?? null;
    }
    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'nombre' => $user['nombre'],
            'correo' => $user['correo'],
            'rol' => $user['rol'],
        ];
    }
    public static function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
