<?php
class Cliente {
    private $conn;
    private $table = 'clientes';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO $this->table (nombre, email, telefono) 
                 VALUES (:nombre, :email, :telefono)";
        $stmt = $this->conn->prepare($query);
        
        $success = $stmt->execute([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?? null
        ]);
        
        return [
            'success' => $success,
            'id' => $success ? $this->conn->lastInsertId() : null
        ];
    }

    public function count() {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function read() {
        $query = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single($id) {
        $query = "SELECT * FROM $this->table WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        return $this->read_single($id);
    }

    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET nombre = :nombre, email = :email, telefono = :telefono WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono']
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}