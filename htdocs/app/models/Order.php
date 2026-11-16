<?php
class Order extends Model {
    public const STATUSES = ['Pendiente', 'En revisión', 'Confirmado', 'Preparando', 'Entregado', 'Cancelado'];
    public const SIZES = ['Pequeño', 'Mediano', 'Grande'];

    public function create(array $d): string {
        $code = 'FA-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->tOrders} (codigo, usuario_id, producto_id, nombre_cliente, correo, tipo_flor, colores, tamano, envoltura, dedicatoria, presupuesto, estado)
             VALUES (:codigo, :usuario_id, :producto_id, :nombre_cliente, :correo, :tipo_flor, :colores, :tamano, :envoltura, :dedicatoria, :presupuesto, 'Pendiente')");
        $stmt->execute([
            'codigo' => $code,
            'usuario_id' => $d['usuario_id'] ?? null,
            'producto_id' => $d['producto_id'] ?? null,
            'nombre_cliente' => $d['nombre_cliente'],
            'correo' => $d['correo'],
            'tipo_flor' => $d['tipo_flor'],
            'colores' => $d['colores'],
            'tamano' => $d['tamano'],
            'envoltura' => $d['envoltura'],
            'dedicatoria' => $d['dedicatoria'],
            'presupuesto' => $d['presupuesto'],
        ]);
        return $code;
    }

    /** Pedidos para el administrador; filtro opcional por estado. */
    public function all(?string $status = null): array {
        $sql = "SELECT o.*, p.nombre AS nombre_producto FROM {$this->tOrders} o
                LEFT JOIN {$this->tProducts} p ON p.id = o.producto_id";
        $params = [];
        if ($status !== null && in_array($status, self::STATUSES, true)) {
            $sql .= " WHERE o.estado = :estado";
            $params['estado'] = $status;
        }
        $stmt = $this->db->prepare($sql . " ORDER BY o.id DESC");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function recent(int $limit = 5): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tOrders} ORDER BY id DESC LIMIT " . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function forUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT o.*, p.nombre AS nombre_producto FROM {$this->tOrders} o
             LEFT JOIN {$this->tProducts} p ON p.id = o.producto_id
             WHERE o.usuario_id = :uid ORDER BY o.id DESC");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        if (!in_array($status, self::STATUSES, true)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE {$this->tOrders} SET estado = :s WHERE id = :id");
        $stmt->execute(['s' => $status, 'id' => $id]);
        return true;
    }

    /** Totales para el panel: total, pendientes y por estado. */
    public function stats(): array {
        $total = (int)$this->db->query("SELECT COUNT(*) FROM {$this->tOrders}")->fetchColumn();
        $pending = (int)$this->db->query("SELECT COUNT(*) FROM {$this->tOrders} WHERE estado IN ('Pendiente','En revisión')")->fetchColumn();
        return ['total' => $total, 'pending' => $pending];
    }
}
