<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
auth();
canAny(['admin', 'atendente']);

require_once __DIR__ . "/../layout/admin_header.php";


$stmt = $pdo->query("
    SELECT t.*, u.nome as atendente_nome 
    FROM turmas t
    LEFT JOIN usuarios u ON u.id = t.usuario_id 
    ORDER BY t.nome ASC
");
$turmas = $stmt->fetchAll();
?>

<style>
    /* Correção crucial para o dropdown não ser cortado */
    .card-body-custom {
        overflow: visible !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto !important;
            /* Mantém scroll apenas no mobile se necessário */
        }
    }

    /* Estilo para garantir que o dropdown sobreponha tudo */
    .dropdown-menu {
        z-index: 1060 !important;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-mortarboard-fill me-2" style="color: var(--ser-blue);"></i>Gestão de Turmas
            </h3>
        </div>
        <a href="turmas_novo.php" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Cadastrar Turma
        </a>
    </div>

    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'turma_em_uso'): ?>
        <div class="alert alert-danger border-0 shadow-sm border-start border-4 border-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Não é possível excluir uma turma com participantes ativos.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['excluido']) || (isset($_GET['msg']) && $_GET['msg'] === 'criado')): ?>
        <div class="alert alert-success border-0 shadow-sm border-start border-4 border-success text-dark">
            <i class="bi bi-check-circle-fill me-2 text-success"></i> Operação realizada com sucesso!
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body card-body-custom p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nome da Turma</th>
                            <th>Atendente Vinculado</th>
                            <th class="text-center">Período</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($turmas as $t): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($t['nome']) ?></td>
                                

                                <td>
                                    <?php if (!empty($t['atendente_nome'])): ?>
                                        <span class="text-dark"><i class="bi bi-person me-1 text-muted"></i><?= htmlspecialchars($t['atendente_nome']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small"><em>Não atribuído</em></span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-normal">
                                        <?= date('d/m/Y', strtotime($t['data_inicio'])) ?>
                                        <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                        <?= date('d/m/Y', strtotime($t['data_fim'])) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $status = strtolower(trim($t['status']));
                                    if ($status === 'ativo' || $status === 'ativa'):
                                    ?>
                                        <span class="badge rounded-pill px-3" style="background-color: var(--ser-blue);">Ativa</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary px-3">Inativa</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> Gerenciar
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <h6 class="dropdown-header">Visualização</h6>
                                            </li>
                                            <li><a class="dropdown-item py-2" href="participantes_lista.php?turma_id=<?= $t['id'] ?>">
                                                    <i class="bi bi-people me-2 text-primary"></i>Ver Participantes</a></li>
                                            <li><a class="dropdown-item py-2" href="sessoes_lista.php?turma_id=<?= $t['id'] ?>">
                                                    <i class="bi bi-calendar-check me-2 text-primary"></i>Sessões / Chamada</a></li>
                                            <li><a class="dropdown-item py-2" href="relatorio_frequencia.php?turma_id=<?= $t['id'] ?>" target="_blank">
                                                    <i class="bi bi-file-pdf me-2 text-danger"></i>Relatório PDF</a></li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header">Configurações</h6>
                                            </li>
                                            <li><a class="dropdown-item py-2" href="turmas_editar.php?id=<?= $t['id'] ?>">
                                                    <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Dados</a></li>

                                            <?php if (isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'admin'): ?>
                                                <li><a class="dropdown-item py-2 text-danger" href="turmas_excluir.php?id=<?= $t['id'] ?>"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta turma?')">
                                                        <i class="bi bi-trash3 me-2"></i>Excluir permanentemente</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-5">
        <a href="dashboard.php" class="btn btn-link text-decoration-none p-0 text-secondary">
            <i class="bi bi-arrow-left"></i> Voltar ao Dashboard
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>