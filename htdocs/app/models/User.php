<?php
class User extends Model {
    public function findByEmail(string $correo): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tUsers} WHERE correo = :correo LIMIT 1");
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nombre, string $correo, string $password, string $rol = 'cliente'): int {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->tUsers} (nombre, correo, contrasena_hash, rol) VALUES (:nombre, :correo, :hash, :rol)");
        $stmt->execute([
            'nombre' => $nombre,
            'correo' => $correo,
            'hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
        ]);
        return (int)$this->db->lastInsertId();
    }

    /** Devuelve el usuario si el correo y la contraseña son correctos. */
    public function authenticate(string $correo, string $password): ?array {
        $user = $this->findByEmail($correo);
        if ($user && password_verify($password, $user['contrasena_hash'])) {
            return $user;
        }
        return null;
    }
}
