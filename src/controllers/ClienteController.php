<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $clienteModel;

    public function __construct($db) {
        $this->clienteModel = new Cliente($db);
    }

    public function getClientes() {
        $stmt = $this->clienteModel->read();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['success' => true, 'clientes' => $clientes];
    }

    public function getClienteById($id) {
        $cliente = $this->clienteModel->read_single($id);
        return $cliente ? ['success' => true, 'cliente' => $cliente] : ['success' => false];
    }

    public function create($data) {
        return $this->clienteModel->create($data);
    }

    public function count() {
        return $this->clienteModel->count();
    }
}