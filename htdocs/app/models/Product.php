<?php
class Product extends Model {
    public const FLOWER_TYPES = ['Natural', 'Sintética', 'Mixta'];
    public const CATEGORIES = ['Ramos', 'Flores eternas', 'Regalos'];

    /** Productos activos para el catálogo público. */
    public function active(): array {
        return $this->db->query(
            "SELECT id, nombre, descripcion, precio_base, categoria, tipo_flor, imagen
             FROM {$this->tProducts} WHERE activo = 1 ORDER BY id ASC")->fetchAll();
    }

    /** Catálogo público (alias de active()). */
    public function all(): array {
        return $this->active();
    }

    /** Todos los productos (activos e inactivos) para el panel admin. */
    public function adminAll(): array {
        return $this->db->query("SELECT * FROM {$this->tProducts} ORDER BY id DESC")->fetchAll();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tProducts} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->tProducts} (nombre, descripcion, precio_base, categoria, tipo_flor, imagen, activo)
             VALUES (:nombre, :descripcion, :precio_base, :categoria, :tipo_flor, :imagen, :activo)");
        $stmt->execute($this->params($d));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $d): void {
        $stmt = $this->db->prepare(
            "UPDATE {$this->tProducts} SET nombre = :nombre, descripcion = :descripcion, precio_base = :precio_base,
             categoria = :categoria, tipo_flor = :tipo_flor, imagen = :imagen, activo = :activo WHERE id = :id");
        $stmt->execute($this->params($d) + ['id' => $id]);
    }

    public function setActive(int $id, bool $active): void {
        $stmt = $this->db->prepare("UPDATE {$this->tProducts} SET activo = :a WHERE id = :id");
        $stmt->execute(['a' => $active ? 1 : 0, 'id' => $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM {$this->tProducts} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function countActive(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM {$this->tProducts} WHERE activo = 1")->fetchColumn();
    }

    private function params(array $d): array {
        return [
            'nombre' => $d['nombre'],
            'descripcion' => $d['descripcion'],
            'precio_base' => $d['precio_base'],
            'categoria' => $d['categoria'],
            'tipo_flor' => $d['tipo_flor'],
            'imagen' => ($d['imagen'] ?? '') !== '' ? $d['imagen'] : null,
            'activo' => !empty($d['activo']) ? 1 : 0,
        ];
    }
}
