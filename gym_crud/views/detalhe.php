<?php
// views/detalhe.php
require __DIR__ . '/layout/header.php';
?>

<div class="container">

    <div class="topo-pagina">
        <h2>Detalhes do Treino</h2>
        <a href="index.php" class="btn btn-cinza">← Voltar</a>
    </div>

    <div class="card-form" style="max-width:700px">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid var(--borda);">
            <h3 style="font-size:1.3rem; color:var(--branco)"><?= htmlspecialchars($treino['nome']) ?></h3>
            <span class="badge"><?= htmlspecialchars($treino['grupo']) ?></span>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.2rem; margin-bottom:1.5rem;">
            <div>
                <div style="font-size:0.75rem; color:var(--muted); text-transform:uppercase; margin-bottom:0.3rem">Data</div>
                <div><?= date('d/m/Y', strtotime($treino['data_treino'])) ?></div>
            </div>
            <div>
                <div style="font-size:0.75rem; color:var(--muted); text-transform:uppercase; margin-bottom:0.3rem">Duração</div>
                <div><?= $treino['duracao'] ?> minutos</div>
            </div>
            <div>
                <div style="font-size:0.75rem; color:var(--muted); text-transform:uppercase; margin-bottom:0.3rem">Registrado em</div>
                <div><?= date('d/m/Y H:i', strtotime($treino['criado_em'])) ?></div>
            </div>
        </div>

        <div>
            <div style="font-size:0.75rem; color:var(--muted); text-transform:uppercase; margin-bottom:0.5rem">Descrição / Exercícios</div>
            <div style="background:var(--escuro); border:1px solid var(--borda); border-radius:5px; padding:1rem; line-height:1.6; white-space:pre-wrap;">
                <?= $treino['descricao'] ? htmlspecialchars($treino['descricao']) : '<span style="color:var(--muted)">Nenhuma descrição informada.</span>' ?>
            </div>
        </div>

        <div class="form-acoes" style="margin-top:1.5rem">
            <a href="index.php?acao=editar&id=<?= $treino['id'] ?>" class="btn btn-laranja">Editar</a>
            <a href="index.php" class="btn btn-cinza">Voltar à lista</a>
        </div>

    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
