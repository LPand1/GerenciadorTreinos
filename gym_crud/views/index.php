<?php
// views/index.php
require __DIR__ . '/layout/header.php';

$mensagens = [
    'criado'        => ['tipo' => 'sucesso', 'texto' => '✔ Treino adicionado com sucesso!'],
    'editado'       => ['tipo' => 'sucesso', 'texto' => '✔ Treino atualizado com sucesso!'],
    'excluido'      => ['tipo' => 'sucesso', 'texto' => '✔ Treino excluído com sucesso!'],
    'nao_encontrado'=> ['tipo' => 'erro',    'texto' => '✘ Treino não encontrado.'],
];

$msg = $_GET['msg'] ?? null;
?>

<div class="container">

    <?php if ($msg && isset($mensagens[$msg])): ?>
        <div class="alerta <?= $mensagens[$msg]['tipo'] ?>">
            <?= $mensagens[$msg]['texto'] ?>
        </div>
    <?php endif; ?>

    <div class="topo-pagina">
        <h2>Meus Treinos</h2>
        <a href="index.php?acao=criar" class="btn btn-laranja">+ Novo Treino</a>
    </div>

    <?php if (empty($treinos)): ?>
        <div class="vazio">
            <p>Nenhum treino cadastrado ainda.</p>
            <a href="index.php?acao=criar" class="btn btn-laranja">Cadastrar primeiro treino</a>
        </div>
    <?php else: ?>
        <div class="tabela-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Grupo</th>
                        <th>Duração</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($treinos as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['nome']) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($t['grupo']) ?></span></td>
                        <td><?= $t['duracao'] ?> min</td>
                        <td><?= date('d/m/Y', strtotime($t['data_treino'])) ?></td>
                        <td>
                            <div class="acoes">
                                <a href="index.php?acao=ver&id=<?= $t['id'] ?>" class="btn btn-verde">Ver</a>
                                <a href="index.php?acao=editar&id=<?= $t['id'] ?>" class="btn btn-cinza">Editar</a>
                                <button class="btn btn-vermelho"
                                    onclick="confirmarExclusao(<?= $t['id'] ?>, '<?= htmlspecialchars($t['nome'], ENT_QUOTES) ?>')">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmação -->
<div class="overlay" id="overlay">
    <div class="modal">
        <h3>⚠ Confirmar exclusão</h3>
        <p>Tem certeza que deseja excluir o treino <strong id="nomeModal"></strong>? Esta ação não pode ser desfeita.</p>
        <div class="modal-acoes">
            <button class="btn btn-cinza"    onclick="fecharModal()">Cancelar</button>
            <button class="btn btn-vermelho" onclick="executarExclusao()">Excluir</button>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
