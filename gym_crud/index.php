<?php
// index.php — Front Controller (ponto de entrada único)

require_once __DIR__ . '/controllers/TreinoController.php';

$controller = new TreinoController();

$acao = $_GET['acao'] ?? 'index';
$id   = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($acao) {
    case 'ver':
        $controller->ver($id);
        break;

    case 'criar':
        $controller->criar();
        break;

    case 'salvar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->salvar();
        }
        break;

    case 'editar':
        $controller->editar($id);
        break;

    case 'atualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->atualizar($id);
        }
        break;

    case 'excluir':
        $controller->excluir($id);
        break;

    case 'historico':
        $controller->historico();
        break;

    default:
        $controller->index();
        break;
}
