<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/auth.php";
require_once __DIR__ . "/../../layout/admin_header.php";

auth();
canAny(['admin','atendente']);

// ==============================
// 📊 MÉTRICAS PRINCIPAIS (Sem Casos)
// ==============================
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalParticipantes = $pdo->query("SELECT COUNT(*) FROM participantes")->fetchColumn();
$totalTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
$totalSessoes = $pdo->query("SELECT COUNT(*) FROM turmas_sessoes")->fetchColumn();

// 🆕 PRESENÇA MÉDIA
$presencaMedia = $pdo->query("
    SELECT 
        ROUND(
            (SUM(CASE WHEN status = 'presente' THEN 1 ELSE 0 END) / COUNT(*)) * 100
        ,1)
    FROM presencas
")->fetchColumn() ?: 0;

// 🆕 ALERTA: SEM FICHA DE INCLUSÃO (Pendências)
$semFicha = $pdo->query("
    SELECT COUNT(*) 
    FROM participantes p
    LEFT JOIN ficha_inclusao fi ON fi.participante_id = p.id
    WHERE fi.id IS NULL
")->fetchColumn();

// ==============================
// 📅 ÚLTIMAS SESSÕES REALIZADAS
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

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Painel de Controle</h2>
            <p class="text-muted">Bem-vindo, <strong><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></strong> (<?= ucfirst($_SESSION['usuario']['nivel']) ?>)</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary px-3 py-2"><?= date('d/m/Y') ?></span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div>
                            <h3 class="text-primary"><?= $totalParticipantes ?></h3>
                            <p class="mb-0">Participantes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people-fill text-primary fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div>
                            <h3 class="text-success"><?= $totalTurmas ?></h3>
                            <p class="mb-0">Turmas Ativas</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-book-fill text-success fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div>
                            <h3 class="text-info"><?= $presencaMedia ?>%</h3>
                            <p class="mb-0">Presença Média</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up-arrow text-info fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger border-4 bg-danger bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <div>
                            <h3 class="text-danger"><?= $semFicha ?></h3>
                            <p class="mb-0">Sem Ficha Inclusão</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded">
            <h6 class="mb-3 text-muted fw-bold">AÇÕES RÁPIDAS</h6>
            <div class="d-flex gap-2 flex-wrap">
                <a href="participantes_novo.php" class="btn btn-primary px-4">
                    <i class="bi bi-person-plus me-1"></i> Novo Participante
                </a>
                <a href="turmas_novo.php" class="btn btn-outline-primary px-4">
                    <i class="bi bi-plus-square me-1"></i> Nova Turma
                </a>
                <a href="participantes_lista.php" class="btn btn-dark px-4">
                    <i class="bi bi-list-ul me-1"></i> Listar Participantes
                </a>
                <a href="sessoes_lista.php" class="btn btn-success px-4">
                    <i class="bi bi-calendar-check me-1"></i> Ver Sessões
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Últimas Sessões</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Turma</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sessoes as $s): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($s['turma']) ?></td>
                                        <td><span class="badge bg-secondary opacity-75"><?= date('d/m/Y', strtotime($s['data'])) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-success"></i>Participantes por Turma</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Turma</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($turmas as $t): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($t['nome']) ?></td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-primary"><?= $t['total'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>