<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Cliente.php';

class PedidoController {
    private $pedidoModel;
    private $productoModel;
    private $clienteModel;

    public function __construct($db) {
        $this->pedidoModel = new Pedido($db);
        $this->productoModel = new Producto($db);
        $this->clienteModel = new Cliente($db);
    }

    public function crearPedido($cliente_id, $productos) {
        try {
            $this->pedidoModel->getConnection()->beginTransaction();

            if (empty($cliente_id) || empty($productos)) {
                throw new Exception("Datos incompletos");
            }

            $cliente = $this->clienteModel->getById($cliente_id);
            if (!$cliente) {
                throw new Exception("Cliente no encontrado");
            }

            $productosValidos = [];
            $total = 0;

            foreach ($productos as $item) {
                if (empty($item['producto_id']) || empty($item['cantidad']) || $item['cantidad'] <= 0) {
                    continue;
                }

                $producto_id = $item['producto_id'];
                $cantidad = (int)$item['cantidad'];

                if (!$this->productoDisponibleParaCliente($cliente_id, $producto_id)) {
                    throw new Exception("Producto no disponible para este cliente");
                }

                $producto = $this->productoModel->getById($producto_id);
                if (!$producto) {
                    throw new Exception("Producto no encontrado");
                }

                if ($producto['stock'] < $cantidad) {
                    throw new Exception("Stock insuficiente para: {$producto['nombre']}");
                }

                $productosValidos[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto['precio']
                ];

                $total += $producto['precio'] * $cantidad;
            }

            if (empty($productosValidos)) {
                throw new Exception("No hay productos válidos en el pedido");
            }

            $pedido_id = $this->pedidoModel->create([
                'cliente_id' => $cliente_id,
                'total' => $total,
                'estado' => 'pendiente'
            ]);

            foreach ($productosValidos as $item) {
                $this->pedidoModel->addDetalle([
                    'pedido_id' => $pedido_id,
                    'producto_id' => $item['producto']['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario']
                ]);

                $nuevo_stock = $item['producto']['stock'] - $item['cantidad'];
                $this->productoModel->updateStock($item['producto']['id'], $nuevo_stock);
            }

            $this->pedidoModel->getConnection()->commit();
            
            return [
                'success' => true,
                'pedido_id' => $pedido_id,
                'total' => $total
            ];

        } catch (Exception $e) {
            $this->pedidoModel->getConnection()->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getPedidoById($id) {
        $pedido = $this->pedidoModel->getById($id);
        
        if (!$pedido) {
            return null;
        }
        
        // Obtener info del cliente
        $pedido['cliente'] = $this->clienteModel->getById($pedido['cliente_id']);
        
        // Obtener detalles del pedido
        $pedido['detalles'] = $this->getDetallesPedido($id);
        
        return $pedido;
    }

    public function getDetallesPedido($pedido_id) {
        $detalles = $this->pedidoModel->getDetalles($pedido_id);
        
        // Enriquecer cada detalle con información del producto
        foreach ($detalles as &$detalle) {
            $producto = $this->productoModel->getById($detalle['producto_id']);
            $detalle['nombre_producto'] = $producto['nombre'] ?? 'Producto desconocido';
            $detalle['imagen_producto'] = $producto['imagen'] ?? null;
        }
        
        return $detalles;
    }

    public function getAllWithClientInfo($filters = []) {
        $pedidos = $this->pedidoModel->getAllWithClientInfo($filters);
        
        foreach ($pedidos as &$pedido) {
            $pedido['estado_label'] = $this->getEstadoLabel($pedido['estado']);
            $pedido['detalles_count'] = $this->pedidoModel->countDetalles($pedido['id']);
        }
        
        return $pedidos;
    }

    public function getRecentPedidos($limit = 5) {
        $pedidos = $this->pedidoModel->getRecent($limit);
        
        foreach ($pedidos as &$pedido) {
            $pedido['cliente'] = $this->clienteModel->getById($pedido['cliente_id']);
        }
        
        return $pedidos;
    }

    public function getPedidosByCliente($cliente_id) {
        return $this->pedidoModel->getByClient($cliente_id);
    }

    public function update($id, $data) {
        try {
            $this->pedidoModel->getConnection()->beginTransaction();
            
            // Validar datos
            if (empty($id) || empty($data)) {
                throw new Exception("Datos incompletos para actualización");
            }
            
            // Validar estado si está presente
            if (isset($data['estado']) && !$this->isEstadoValido($data['estado'])) {
                throw new Exception("Estado de pedido no válido");
            }
            
            // Actualizar pedido
            $success = $this->pedidoModel->update($id, $data);
            
            if (!$success) {
                throw new Exception("Error al actualizar el pedido");
            }
            
            // Si se cambia el estado a "completado" o "cancelado", registrar fecha
            if (isset($data['estado']) && in_array($data['estado'], ['completado', 'cancelado'])) {
                $field = $data['estado'] === 'completado' ? 'fecha_completado' : 'fecha_cancelado';
                $this->pedidoModel->update($id, [$field => date('Y-m-d H:i:s')]);
            }
            
            $this->pedidoModel->getConnection()->commit();
            
            return [
                'success' => true,
                'message' => 'Pedido actualizado correctamente',
                'pedido' => $this->getPedidoById($id)
            ];
        } catch (Exception $e) {
            $this->pedidoModel->getConnection()->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateEstado($id, $estado) {
        if (!$this->isEstadoValido($estado)) {
            return [
                'success' => false,
                'message' => 'Estado no válido'
            ];
        }
        
        return $this->update($id, ['estado' => $estado]);
    }

    public function delete($id) {
        try {
            $this->pedidoModel->getConnection()->beginTransaction();
            
            // Verificar que el pedido existe
            $pedido = $this->getPedidoById($id);
            if (!$pedido) {
                throw new Exception("Pedido no encontrado");
            }
            
            // No permitir eliminar pedidos completados o cancelados
            if (in_array($pedido['estado'], ['completado', 'cancelado'])) {
                throw new Exception("No se puede eliminar un pedido {$pedido['estado']}");
            }
            
            // Restaurar stock si el pedido está en proceso
            if ($pedido['estado'] === 'procesando') {
                foreach ($pedido['detalles'] as $detalle) {
                    $producto = $this->productoModel->getById($detalle['producto_id']);
                    $nuevo_stock = $producto['stock'] + $detalle['cantidad'];
                    $this->productoModel->updateStock($detalle['producto_id'], $nuevo_stock);
                }
            }
            
            // Eliminar pedido
            $success = $this->pedidoModel->delete($id);
            
            if (!$success) {
                throw new Exception("Error al eliminar el pedido");
            }
            
            $this->pedidoModel->getConnection()->commit();
            
            return [
                'success' => true,
                'message' => 'Pedido eliminado correctamente'
            ];
        } catch (Exception $e) {
            $this->pedidoModel->getConnection()->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function count($filters = []) {
        return $this->pedidoModel->count($filters);
    }

    public function getEstados() {
        return [
            'pendiente' => 'Pendiente',
            'procesando' => 'Procesando',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado'
        ];
    }

    private function productoDisponibleParaCliente($cliente_id, $producto_id) {
        $query = "SELECT COUNT(*) FROM client_product 
                 WHERE cliente_id = ? AND producto_id = ?";
        $stmt = $this->pedidoModel->getConnection()->prepare($query);
        $stmt->execute([$cliente_id, $producto_id]);
        return $stmt->fetchColumn() > 0;
    }

    private function isEstadoValido($estado) {
        $estadosValidos = array_keys($this->getEstados());
        return in_array($estado, $estadosValidos);
    }

    private function getEstadoLabel($estado) {
        $estados = $this->getEstados();
        return $estados[$estado] ?? $estado;
    }
}