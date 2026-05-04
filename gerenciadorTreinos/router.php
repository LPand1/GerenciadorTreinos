<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/TreinoController.php';
require_once __DIR__ . '/controllers/ExercicioController.php';
require_once __DIR__ . '/controllers/SessaoController.php';

header('Content-Type: application/json');

$pdo    = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o nome da pasta raiz da URI caso exista
// Ex: /gerenciadorTreinos/treinos → /treinos
$base = '/gerenciadorTreinos';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

$uri = rtrim($uri, '/') ?: '/';

// Divide a URI em segmentos: /treinos/1 → ['treinos', '1']
$segmentos = explode('/', ltrim($uri, '/'));
$recurso   = $segmentos[0] ?? '';
$id        = isset($segmentos[1]) && is_numeric($segmentos[1]) ? (int) $segmentos[1] : null;

// Roteamento
match ($recurso) {
    'treinos' => (function () use ($method, $id, $pdo) {
        $controller = new TreinoController($pdo);
        match (true) {
            $method === 'GET'    && $id === null => $controller->index(),
            $method === 'GET'    && $id !== null => $controller->show($id),
            $method === 'POST'                   => $controller->store(),
            $method === 'PUT'    && $id !== null => $controller->update($id),
            $method === 'DELETE' && $id !== null => $controller->destroy($id),
            default => resposta404(),
        };
    })(),

    'exercicios' => (function () use ($method, $id, $pdo) {
        $controller = new ExercicioController($pdo);
        match (true) {
            $method === 'POST'                   => $controller->store(),
            $method === 'PUT'    && $id !== null => $controller->update($id),
            $method === 'DELETE' && $id !== null => $controller->destroy($id),
            default => resposta404(),
        };
    })(),

    'sessoes' => (function () use ($method, $id, $pdo) {
        $controller = new SessaoController($pdo);
        match (true) {
            $method === 'GET'    && $id === null => $controller->index(),
            $method === 'GET'    && $id !== null => $controller->show($id),
            $method === 'POST'                   => $controller->store(),
            $method === 'PUT'    && $id !== null => $controller->update($id),
            $method === 'DELETE' && $id !== null => $controller->destroy($id),
            default => resposta404(),
        };
    })(),

    default => resposta404(),
};

function resposta404(): void {
    http_response_code(404);
    echo json_encode(['erro' => 'Rota não encontrada.']);
}