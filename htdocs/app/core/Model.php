<?php
require_once __DIR__ . '/../config/database.php';

/** Clase base de los modelos: entrega la conexión PDO y los nombres de las tablas. */
abstract class Model {
    protected PDO $db;
    // Nombres de las tablas (se cambian en app/config/database.php o database.local.php).
    protected string $tUsers = T_USUARIOS;
    protected string $tProducts = T_PRODUCTOS;
    protected string $tOrders = T_PEDIDOS;
    public function __construct() {
        $this->db = getPDO();
    }
}
