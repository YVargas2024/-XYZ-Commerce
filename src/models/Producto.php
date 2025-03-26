<?php
class Producto {
    private $conn;
    private $table = 'productos';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAvailable() {
        $query = "SELECT * FROM " . $this->table . " WHERE stock > 0 ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStock($id, $nuevoStock) {
        $query = "UPDATE " . $this->table . " SET stock = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nuevoStock, $id]);
    }

    public function create($data) {
        $query = "INSERT INTO productos (nombre, precio, stock) VALUES (:nombre, :precio, :stock)";
        $stmt = $this->conn->prepare($query);
        
        $success = $stmt->execute([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'stock' => $data['stock']
        ]);
        
        return [
            'success' => $success,
            'producto_id' => $success ? $this->conn->lastInsertId() : null
        ];
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET nombre = :nombre, precio = :precio, stock = :stock WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'stock' => $data['stock']
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}