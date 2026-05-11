<?php
// views/historico.php
require __DIR__ . '/layout/header.php';

// Calcular estatísticas
$totalTreinos  = count($treinos);
$totalMinutos  = array_sum(array_column($treinos, 'duracao'));
$totalHoras    = $totalMinutos > 0 ? round($totalMinutos / 60, 1) : 0;

// Grupo mais treinado
$grupos = array_column($treinos, 'grupo');
$grupoCont = array_count_values($grupos);
arsort($grupoCont);
$grupoTop = !empty($grupoCont) ? array_key_first($grupoCont) : '-';
?>

<div class="container">

    <div class="topo-pagina">
        <h2>Histórico de Treinos</h2>
        <a href="index.php" class="btn btn-cinza">← Voltar</a>
    </div>

    <!-- Cards de estatísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="numero"><?= $totalTreinos ?></div>
            <div class="label">Treinos Registrados</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= $totalMinutos ?></div>
            <div class="label">Minutos Totais</div>
        </div>
        <div class="stat-card">
            <div class="numero"><?= $totalHoras ?>h</div>
            <div class="label">Horas Totais</div>
        </div>
        <div class="stat-card">
            <div class="numero" style="font-size:1.2rem"><?= htmlspecialchars($grupoTop) ?></div>
            <div class="label">Grupo Favorito</div>
        </div>
    </div>

    <?php if (empty($treinos)): ?>
        <div class="vazio">
            <p>Nenhum treino no histórico.</p>
            <a href="index.php?acao=criar" class="btn btn-laranja">Registrar treino</a>
        </div>
    <?php else: ?>
        <div class="tabela-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Nome</th>
                        <th>Grupo</th>
                        <th>Duração</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($treinos as $t): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($t['data_treino'])) ?></td>
                        <td><?= htmlspecialchars($t['nome']) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($t['grupo']) ?></span></td>
                        <td><?= $t['duracao'] ?> min</td>
                        <td><?= $t['descricao'] ? htmlspecialchars(mb_substr($t['descricao'], 0, 60)) . '...' : '<span style="color:var(--muted)">—</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
