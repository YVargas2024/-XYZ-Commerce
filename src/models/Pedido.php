<?php
class Pedido {
    private $conn;
    private $table = 'pedidos';
    private $detalleTable = 'detalles_pedido';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConnection() {
        return $this->conn;
    }

    // Crear un nuevo pedido
    public function create($data) {
        $query = "INSERT INTO {$this->table} (cliente_id, total, estado, fecha) 
                 VALUES (:cliente_id, :total, :estado, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'cliente_id' => $data['cliente_id'],
            'total' => $data['total'],
            'estado' => $data['estado'] ?? 'pendiente'
        ]);
        return $this->conn->lastInsertId();
    }

    // Añadir detalle al pedido
    public function addDetalle($data) {
        $query = "INSERT INTO {$this->detalleTable} 
                 (pedido_id, producto_id, cantidad, precio_unitario) 
                 VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    // Obtener pedido por ID con info de cliente
    public function getById($id) {
        $query = "SELECT p.*, c.nombre as cliente_nombre, c.email as cliente_email, 
                 c.telefono as cliente_telefono 
                 FROM {$this->table} p
                 INNER JOIN clientes c ON p.cliente_id = c.id
                 WHERE p.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener detalles de un pedido
    public function getDetalles($pedido_id) {
        $query = "SELECT d.*, p.nombre as producto_nombre, p.precio as precio_actual
                 FROM {$this->detalleTable} d
                 JOIN productos p ON d.producto_id = p.id
                 WHERE d.pedido_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los pedidos con info de cliente
    public function getAllWithClientInfo() {
        $query = "SELECT p.*, c.nombre as cliente_nombre 
                 FROM {$this->table} p
                 LEFT JOIN clientes c ON p.cliente_id = c.id
                 ORDER BY p.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener pedidos por cliente
    public function getByClient($cliente_id) {
        $query = "SELECT p.* FROM {$this->table} p
                 WHERE p.cliente_id = ?
                 ORDER BY p.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total de pedidos
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Obtener pedidos recientes
    public function getRecent($limit = 5) {
        $query = "SELECT p.*, c.nombre as cliente_nombre 
                 FROM {$this->table} p
                 LEFT JOIN clientes c ON p.cliente_id = c.id
                 ORDER BY p.fecha DESC 
                 LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar estado de pedido
    public function updateEstado($id, $estado) {
        $query = "UPDATE {$this->table} SET estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$estado, $id]);
    }

    // Actualizar pedido (completo)
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET cliente_id = :cliente_id, total = :total, 
                 estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'cliente_id' => $data['cliente_id'],
            'total' => $data['total'],
            'estado' => $data['estado']
        ]);
    }

    // Eliminar pedido
    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            
            // 1. Eliminar detalles del pedido
            $query = "DELETE FROM {$this->detalleTable} WHERE pedido_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            // 2. Eliminar el pedido
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>