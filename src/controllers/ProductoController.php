<?php
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $model;

    public function __construct($db) {
        $this->model = new Producto($db);
    }

    public function count() {
        return $this->model->count();
    }

    public function getAll() {
        return $this->model->getAll();
    }

    public function getProductosDisponibles() {
        return $this->model->getAvailable();
    }

    public function getById($id) {
        return $this->model->getById($id);
    }

    public function showAll() {
        $productos = $this->getAll();
        include __DIR__ . '/../views/productos/list.php';
    }

    public function create($data) {
        // Validación básica
        if (empty($data['nombre']) || !is_numeric($data['precio']) || !is_numeric($data['stock'])) {
            return ['success' => false, 'message' => 'Datos inválidos'];
        }

        return $this->model->create($data);
    }

    public function updateStock($id, $nuevoStock) {
        return $this->model->updateStock($id, $nuevoStock);
    }
}