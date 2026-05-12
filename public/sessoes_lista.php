<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

// Buscar turma e total de matriculados para cálculo de porcentagem
// Buscar apenas os dados da turma (sem tentar contar participantes vinculados)
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

// Defina como zero para não quebrar o cálculo de porcentagem abaixo
$turma['total_alunos'] = 0; 

if (!$turma) {
    die("Turma não encontrada");
}

if (!$turma) {
    die("Turma não encontrada");
}

// Buscar sessões
$stmt = $pdo->prepare("
    SELECT s.*, 
    (SELECT COUNT(*) FROM presencas p 
     WHERE p.sessao_id = s.id AND p.status = 'presente') as presentes
    FROM turmas_sessoes s
    WHERE s.turma_id = ?
    ORDER BY s.data DESC
");
$stmt->execute([$turma_id]);
$sessoes = $stmt->fetchAll();

require_once __DIR__ . '/../layout/admin_header.php';
?>

<style>
    .sessao-card {
        transition: transform 0.2s;
        border-left: 5px solid var(--ser-blue) !important;
    }
    .sessao-card:hover {
        transform: translateY(-5px);
    }
    .date-badge {
        background: #f8f9fa;
        color: #333;
        padding: 5px 10px;
        border-radius: 8px;
        font-weight: bold;
        border: 1px solid #dee2e6;
    }
</style>

<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="turmas_lista.php">Turmas</a></li>
                    <li class="breadcrumb-item active">Sessões</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark"><i class="bi bi-calendar3 me-2 text-primary"></i>Sessões da Turma: <?= htmlspecialchars($turma['nome']) ?></h3>
        </div>
        <a href="sessao_criar.php?turma_id=<?= $turma_id ?>" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nova Sessão
        </a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> Presenças registradas com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (count($sessoes) > 0): ?>
            <?php foreach ($sessoes as $s): 
                $percentual = ($turma['total_alunos'] > 0) ? ($s['presentes'] / $turma['total_alunos']) * 100 : 0;
            ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 sessao-card">
                        <div class="card-body d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="date-badge">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <?= date('d/m/Y', strtotime($s['data'])) ?>
                                </div>
                                <span class="badge rounded-pill <?= $percentual > 70 ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= round($percentual) ?>% adesão
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2">
                                <?= htmlspecialchars($s['descricao']) ?: '<span class="text-muted fw-normal italic">Sessão sem título</span>' ?>
                            </h5>
                            
                            <p class="text-muted small mb-3">
                                <i class="bi bi-people-fill me-1"></i> <?= $s['presentes'] ?> de <?= $turma['total_alunos'] ?> participantes presentes
                            </p>

                            <div class="progress mb-4" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentual ?>%"></div>
                            </div>

                            <div class="mt-auto">
                                <a href="sessao_presenca.php?sessao_id=<?= $s['id'] ?>" class="btn btn-primary w-100 mb-2 shadow-sm">
                                    <i class="bi bi-ui-checks me-1"></i> Presenças
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="sessao_editar.php?id=<?= $s['id'] ?>" class="btn btn-outline-warning btn-sm flex-grow-1">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="sessao_excluir.php?id=<?= $s['id'] ?>&turma_id=<?= $turma_id ?>" 
                                       class="btn btn-outline-danger btn-sm flex-grow-1"
                                       onclick="return confirm('Tem certeza que deseja excluir esta sessão e todos os registros de presença vinculados?')">
                                        <i class="bi bi-trash"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <i class="bi bi-calendar-x display-4 text-muted"></i>
                    <p class="mt-3 text-muted">Nenhuma sessão encontrada para esta turma.</p>
                    <a href="sessao_criar.php?turma_id=<?= $turma_id ?>" class="btn btn-primary btn-sm">Criar primeira sessão</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <a href="turmas_lista.php" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Voltar para Turmas
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>