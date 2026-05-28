<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();

$id = $_GET['id'] ?? null;
if (!$id) die("Participante não informado");

// Participante + turma
$stmt = $pdo->prepare("
    SELECT p.*, t.nome AS turma_nome
    FROM participantes p
    LEFT JOIN turmas t ON t.id = p.turma_id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Verificar fichas (Status do acompanhamento)
$stmt = $pdo->prepare("SELECT id FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$id]);
$inclusao = $stmt->fetch();

$stmt = $pdo->prepare("SELECT id FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$id]);
$final = $stmt->fetch(); // Aqui definimos a variável $final


// Função helper para exibir campos vazios com elegância
function campo($valor)
{
    return $valor ? htmlspecialchars($valor) : '<span class="text-muted small italic">Não informado</span>';
}
?>

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="participantes_lista.php" class="btn btn-link text-decoration-none p-0 mb-2 text-secondary">
            <i class="bi bi-arrow-left"></i> Voltar para a lista
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-vcard-fill me-2" style="color: var(--ser-blue);"></i>Prontuário do Participante
            </h3>
            <a href="participantes_editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil me-1"></i> Editar Cadastro
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-secondary">Informações Cadastrais</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="small text-uppercase fw-bold text-muted d-block mb-1">Nome Completo</label>
                            <span class="fs-5 fw-bold text-dark"><?= htmlspecialchars($p['nome']) ?></span>
                        </div>

                        <div class="col-md-4">
                            <label class="small text-uppercase fw-bold text-muted d-block mb-1">Nº do Processo</label>
                            <code class="fs-6 text-primary fw-bold"><?= campo($p['numero_processo']) ?></code>
                        </div>

                        <div class="col-md-4">
                            <label class="small text-uppercase fw-bold text-muted d-block mb-1">Turma Vinculada</label>
                            <span class="badge bg-light text-dark border fw-normal fs-6">
                                <i class="bi bi-mortarboard me-1"></i> <?= campo($p['turma_nome']) ?>
                            </span>
                        </div>

                        <div class="col-md-4">
                            <label class="small text-uppercase fw-bold text-muted d-block mb-1">Total de Passagens</label>
                            <span class="badge rounded-pill px-3 py-2" style="background-color: var(--ser-blue);">
                                <?= $p['total_passagens'] ?? 0 ?> passagens
                            </span>
                        </div>

                        <?php if (!empty($p['observacoes'])): ?>
                            <div class="col-12">
                                <div class="bg-light p-3 rounded border-start border-4 border-warning">
                                    <label class="small text-uppercase fw-bold text-muted d-block mb-1">Observações do Cadastro</label>
                                    <p class="mb-0 text-dark small"><?= nl2br(htmlspecialchars($p['observacoes'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm border-top border-4" style="border-color: var(--ser-orange) !important;">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Acompanhamento</h5>

                    <div class="d-grid gap-3">

                        <div class="p-3 rounded border <?= $inclusao ? 'bg-light-success' : 'bg-light' ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">1. Inclusão</h6>
                                <?php if ($inclusao): ?>
                                    <span class="badge bg-success small">Concluído</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark small">Pendente</span>
                                <?php endif; ?>
                            </div>
                            <?php if (!$inclusao): ?>
                                <a href="ficha_inclusao.php?participante_id=<?= $p['id'] ?>" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-plus-lg me-1"></i> Preencher Inclusão
                                </a>
                            <?php else: ?>
                                <a href="ficha_inclusao_ver.php?participante_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="bi bi-eye me-1"></i> Visualizar Ficha
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="p-3 rounded border <?= $final ? 'bg-light-success' : 'bg-light' ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">2. Avaliação Final</h6>
                                <?php if ($final): ?>
                                    <span class="badge bg-success small">Concluído</span>
                                <?php elseif (!$inclusao): ?>
                                    <span class="badge bg-secondary small">Bloqueado</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark small">Pendente</span>
                                <?php endif; ?>
                            </div>

                            <?php if (!$inclusao): ?>
                                <p class="small text-muted mb-0"><i class="bi bi-lock-fill me-1"></i> Libera após a inclusão.</p>
                            <?php elseif (!$final): ?>
                                <a href="ficha_final.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-dark w-100">
                                    <i class="bi bi-pencil-square me-1"></i> Preencher Final
                                </a>
                            <?php else: ?>
                                <a href="ficha_final_ver.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-success w-100">
                                    <i class="bi bi-eye me-1"></i> Visualizar Ficha
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr class="my-4">

                    <div class="d-grid">
                        <button class="btn btn-light border text-danger btn-sm" onclick="alert('Funcionalidade de Relatório Individual em breve.')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Exportar Histórico (PDF)
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-success {
        background-color: #f0fff4;
        border-color: #c6f6d5 !important;
    }

    label {
        letter-spacing: 0.5px;
    }

    .card {
        border-radius: 12px;
    }
</style>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>