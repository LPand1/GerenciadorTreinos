<?php
// views/layout/header.php
$paginaAtual = basename($_SERVER['PHP_SELF']) . '?' . ($_SERVER['QUERY_STRING'] ?? '');
$acaoAtual   = $_GET['acao'] ?? 'index';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymCRUD — Gerenciador de Treinos</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<header>
    <h1>💪 <span>Gym</span>CRUD</h1>
    <nav>
        <a href="index.php" class="<?= $acaoAtual === 'index' ? 'ativo' : '' ?>">Treinos</a>
        <a href="index.php?acao=criar" class="<?= $acaoAtual === 'criar' ? 'ativo' : '' ?>">+ Novo</a>
        <a href="index.php?acao=historico" class="<?= $acaoAtual === 'historico' ? 'ativo' : '' ?>">Histórico</a>
    </nav>
</header>
