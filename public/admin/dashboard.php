<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/auth.php";
require_once __DIR__ . "/../../layout/admin_header.php";

auth();
canAny(['admin','atendente']);

// ==============================
// 📊 MÉTRICAS PRINCIPAIS
// ==============================
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalParticipantes = $pdo->query("SELECT COUNT(*) FROM participantes")->fetchColumn();
$totalTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
$totalPre = $pdo->query("SELECT COUNT(*) FROM pre_cadastros WHERE status = 'pendente'")->fetchColumn();

// 🆕 CASOS
$totalCasos = $pdo->query("SELECT COUNT(*) FROM casos")->fetchColumn();
$casosAtivos = $pdo->query("SELECT COUNT(*) FROM casos WHERE status = 'ativo'")->fetchColumn();

// 🆕 SESSÕES
$totalSessoes = $pdo->query("SELECT COUNT(*) FROM turmas_sessoes")->fetchColumn();

// 🆕 PRESENÇA MÉDIA
$presencaMedia = $pdo->query("
    SELECT 
        ROUND(
            (SUM(CASE WHEN status = 'presente' THEN 1 ELSE 0 END) / COUNT(*)) * 100
        ,1)
    FROM presencas
")->fetchColumn();

// 🆕 ALERTA: SEM FICHA
$semFicha = $pdo->query("
    SELECT COUNT(*) 
    FROM participantes p
    LEFT JOIN ficha_inclusao fi ON fi.participante_id = p.id
    LEFT JOIN ficha_avaliacao_final ff ON ff.participante_id = p.id
    WHERE 
        (p.tipo = 'vitima' AND fi.id IS NULL)
        OR (p.tipo = 'autor' AND ff.id IS NULL)
")->fetchColumn();

// ==============================
// 📋 ÚLTIMOS PRÉ-CADASTROS
// ==============================
$recentes = $pdo->query("
    SELECT nome, criado_em
    FROM pre_cadastros
    ORDER BY criado_em DESC
    LIMIT 5
");

// ==============================
// 📅 ÚLTIMAS SESSÕES
// ==============================
$sessoes = $pdo->query("
    SELECT ts.data, t.nome AS turma
    FROM turmas_sessoes ts
    JOIN turmas t ON t.id = ts.turma_id
    ORDER BY ts.data DESC
    LIMIT 5
");

// ==============================
// 📊 PARTICIPANTES POR TURMA
// ==============================
$turmas = $pdo->query("
    SELECT 
        t.nome,
        COUNT(p.id) as total
    FROM turmas t
    LEFT JOIN participantes p ON p.turma_id = t.id
    GROUP BY t.id
    ORDER BY total DESC
");
?>

<h2 class="mb-4">Dashboard</h2>

<p>
    Bem-vindo, <strong><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></strong>
</p>

<p class="text-muted">
    Nível: <?= ucfirst($_SESSION['usuario']['nivel']) ?>
</p>

<!-- ============================== -->
<!-- 📊 CARDS PRINCIPAIS -->
<!-- ============================== -->
<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Participantes</h6>
                <h3><?= $totalParticipantes ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Casos Ativos</h6>
                <h3><?= $casosAtivos ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Sessões</h6>
                <h3><?= $totalSessoes ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center bg-warning bg-opacity-10">
            <div class="card-body">
                <h6 class="text-muted">Pré-Cadastros</h6>
                <h3 class="text-warning"><?= $totalPre ?></h3>
            </div>
        </div>
    </div>

</div>

<!-- ============================== -->
<!-- 📊 SEGUNDA LINHA -->
<!-- ============================== -->
<div class="row g-4 mt-1">

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Usuários</h6>
                <h3><?= $totalUsuarios ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Casos Totais</h6>
                <h3><?= $totalCasos ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Presença Média</h6>
                <h3><?= $presencaMedia ?: 0 ?>%</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center bg-danger bg-opacity-10">
            <div class="card-body">
                <h6 class="text-muted">⚠️ Sem Ficha</h6>
                <h3 class="text-danger"><?= $semFicha ?></h3>
            </div>
        </div>
    </div>

</div>

<!-- ============================== -->
<!-- 🔘 AÇÕES RÁPIDAS -->
<!-- ============================== -->
<div class="row mt-4 g-3">

    <div class="col-md-2">
        <a href="turmas_novo.php" class="btn btn-primary w-100">📚 Nova Turma</a>
    </div>

    <div class="col-md-2">
        <a href="pre_cadastros.php" class="btn btn-warning w-100">📋 Pré-Cadastros</a>
    </div>

    <div class="col-md-2">
        <a href="participantes_lista.php" class="btn btn-dark w-100">👥 Participantes</a>
    </div>

    <div class="col-md-2">
        <a href="turmas_lista.php" class="btn btn-dark w-100">📚 Turmas</a>
    </div>

    <div class="col-md-2">
        <a href="casos_lista.php" class="btn btn-danger w-100">⚖️ Casos</a>
    </div>

    <div class="col-md-2">
        <a href="sessoes_lista.php" class="btn btn-success w-100">📅 Sessões</a>
    </div>

</div>

<!-- ============================== -->
<!-- 📋 ÚLTIMOS DADOS -->
<!-- ============================== -->
<div class="row mt-4">

    <!-- PRÉ-CADASTROS -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Últimos Pré-Cadastros</h5>

                <table class="table table-sm">
                    <tr>
                        <th>Nome</th>
                        <th>Data</th>
                    </tr>

                    <?php foreach ($recentes as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nome']) ?></td>
                            <td><?= date('d/m/Y', strtotime($r['criado_em'])) ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </div>

    <!-- SESSÕES -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Últimas Sessões</h5>

                <table class="table table-sm">
                    <tr>
                        <th>Turma</th>
                        <th>Data</th>
                    </tr>

                    <?php foreach ($sessoes as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['turma']) ?></td>
                            <td><?= date('d/m/Y', strtotime($s['data'])) ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- ============================== -->
<!-- 📊 PARTICIPANTES POR TURMA -->
<!-- ============================== -->
<div class="card mt-4 shadow-sm">
    <div class="card-body">

        <h5>Participantes por Turma</h5>

        <table class="table table-sm">
            <tr>
                <th>Turma</th>
                <th>Total</th>
            </tr>

            <?php foreach ($turmas as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nome']) ?></td>
                    <td><?= $t['total'] ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    </div>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>