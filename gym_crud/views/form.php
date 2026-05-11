<?php
// views/form.php
// Usado tanto para criar quanto para editar

require __DIR__ . '/layout/header.php';

$editando = isset($treino) && !empty($treino['id']);
$acao     = $editando ? "index.php?acao=atualizar&id={$treino['id']}" : "index.php?acao=salvar";
$titulo   = $editando ? "Editar Treino" : "Novo Treino";

// Preenche campos com POST em caso de erro, ou com $treino se editando
$v = [
    'nome'        => htmlspecialchars($_POST['nome']        ?? $treino['nome']        ?? ''),
    'descricao'   => htmlspecialchars($_POST['descricao']   ?? $treino['descricao']   ?? ''),
    'grupo'       => htmlspecialchars($_POST['grupo']       ?? $treino['grupo']       ?? ''),
    'duracao'     => htmlspecialchars($_POST['duracao']     ?? $treino['duracao']     ?? ''),
    'data_treino' => htmlspecialchars($_POST['data_treino'] ?? $treino['data_treino'] ?? date('Y-m-d')),
];

$grupos = ['Peito', 'Costas', 'Pernas', 'Ombros', 'Bíceps', 'Tríceps', 'Abdômen', 'Glúteos', 'Full Body', 'Upper', 'Lower'];
?>

<div class="container">
    <div class="card-form">
        <h2><?= $titulo ?></h2>

        <?php if (!empty($erros)): ?>
            <div class="alerta erro">
                <?php foreach ($erros as $e): ?>
                    <div>✘ <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= $acao ?>" method="POST" id="formTreino" novalidate>

            <div class="campo">
                <label for="nome">Nome do Treino *</label>
                <input type="text" id="nome" name="nome"
                       value="<?= $v['nome'] ?>"
                       placeholder="Ex: Peito e Tríceps" maxlength="100">
                <span class="erro-campo"></span>
            </div>

            <div class="campo">
                <label for="grupo">Grupo Muscular *</label>
                <select id="grupo" name="grupo">
                    <option value="">Selecione...</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= $g ?>" <?= $v['grupo'] === $g ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="erro-campo"></span>
            </div>

            <div class="campo">
                <label for="duracao">Duração (minutos) *</label>
                <input type="number" id="duracao" name="duracao"
                       value="<?= $v['duracao'] ?>"
                       min="1" max="300" placeholder="Ex: 60">
                <span class="erro-campo"></span>
            </div>

            <div class="campo">
                <label for="data_treino">Data do Treino *</label>
                <input type="date" id="data_treino" name="data_treino"
                       value="<?= $v['data_treino'] ?>">
                <span class="erro-campo"></span>
            </div>

            <div class="campo">
                <label for="descricao">Descrição / Exercícios</label>
                <textarea id="descricao" name="descricao"
                          placeholder="Ex: Supino reto 4x10, Crucifixo 3x12..."><?= $v['descricao'] ?></textarea>
            </div>

            <div class="form-acoes">
                <button type="submit" class="btn btn-laranja">
                    <?= $editando ? 'Salvar Alterações' : 'Cadastrar Treino' ?>
                </button>
                <a href="index.php" class="btn btn-cinza">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
