<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../layout/admin_header.php";

auth();
canAny(['admin', 'atendente']);

// --- BUSCA DE MÉTRICAS ---
$totalParticipantes = $pdo->query("SELECT COUNT(*) FROM participantes")->fetchColumn();
$totalTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();

$presencaMedia = $pdo->query("
    SELECT ROUND((SUM(CASE WHEN status = 'presente' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1)
    FROM presencas
")->fetchColumn() ?: 0;

$semFicha = $pdo->query("
    SELECT COUNT(*) 
    FROM participantes p
    LEFT JOIN ficha_inclusao fi ON fi.participante_id = p.id
    WHERE fi.id IS NULL
")->fetchColumn();

$sessoes = $pdo->query("
    SELECT ts.data, t.nome AS turma
    FROM turmas_sessoes ts
    JOIN turmas t ON t.id = ts.turma_id
    ORDER BY ts.data DESC LIMIT 5
");

$turmas = $pdo->query("
    SELECT t.nome, COUNT(p.id) as total
    FROM turmas t
    LEFT JOIN participantes p ON p.turma_id = t.id
    GROUP BY t.id
    ORDER BY total DESC
");
?>

<style>
    .card-metric { transition: transform 0.2s; border: none; }
    .card-metric:hover { transform: translateY(-5px); }
    .icon-shape { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
    .bg-ser-blue { background-color: var(--ser-blue); }
    .bg-ser-orange { background-color: var(--ser-orange); }
    .bg-ser-wine { background-color: var(--ser-wine); }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Painel de Controle</h2>
            <p class="text-muted">Olá, <span class="text-primary fw-semibold"><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></span>. Veja o resumo do projeto hoje.</p>
        </div>
        <div class="text-end">
            <div class="bg-white shadow-sm rounded-pill px-3 py-2 fw-bold text-secondary border">
                <i class="bi bi-calendar-event me-2 text-primary"></i><?= date('d/m/Y') ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-metric shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">Participantes</p>
                            <h2 class="mb-0 fw-bold text-dark"><?= $totalParticipantes ?></h2>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-metric shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">Turmas Ativas</p>
                            <h2 class="mb-0 fw-bold text-dark"><?= $totalTurmas ?></h2>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-mortarboard-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-metric shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">Presença Média</p>
                            <h2 class="mb-0 fw-bold text-dark"><?= $presencaMedia ?>%</h2>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-10 text-info">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-metric shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">Pendências</p>
                            <h2 class="mb-0 fw-bold text-danger"><?= $semFicha ?></h2>
                        </div>
                        <div class="icon-shape bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-exclamation-octagon-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3 d-flex align-items-center gap-3">
            <span class="fw-bold text-muted small border-end pe-3">AÇÕES RÁPIDAS</span>
            <div class="d-flex gap-2">
                <a href="participantes_cadastrar.php" class="btn btn-sm btn-primary rounded-pill px-3">
                    <i class="bi bi-person-plus me-1"></i> Novo Participante
                </a>
                <a href="turmas_novo.php" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                    <i class="bi bi-plus-circle me-1 text-dark"></i> Nova Turma
                </a>
                <a href="participantes_lista.php" class="btn btn-sm btn-dark rounded-pill px-3">
                    <i class="bi bi-list-check me-1"></i> Ver Lista
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-ser-blue"><i class="bi bi-clock-history me-2"></i>Últimas Atividades</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-4">Turma</th>
                                    <th class="text-end pe-4">Data da Sessão</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sessoes as $s): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($s['turma']) ?></td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-light text-secondary border fw-normal"><?= date('d/m/Y', strtotime($s['data'])) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-ser-orange"><i class="bi bi-bar-chart-fill me-2"></i>Distribuição por Turma</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-4">Nome da Turma</th>
                                    <th class="text-center pe-4">Total Alunos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($turmas as $t): ?>
                                <tr>
                                    <td class="ps-4"><?= htmlspecialchars($t['nome']) ?></td>
                                    <td class="text-center pe-4">
                                        <span class="badge rounded-pill bg-ser-blue px-3"><?= $t['total'] ?></span>
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

<?php require_once __DIR__ . "/../layout/admin_footer.php"; ?>