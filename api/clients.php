<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../src/controllers/ClienteController.php';

$database = new Database();
$db = $database->connect();

$controller = new ClienteController($db);
$response = $controller->getClientes();

echo json_encode($response);
?>