<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . '/../config/auth.php';
auth();
canAny(['admin', 'atendente']);

require_once __DIR__ . "/../layout/admin_header.php";

$turma_id = $_GET['turma_id'] ?? null;
$busca    = $_GET['busca'] ?? null;

$sql = "
    SELECT p.id, p.nome, p.numero_processo, p.total_passagens, p.observacoes, t.nome AS turma
    FROM participantes p
    JOIN turmas t ON t.id = p.turma_id
    WHERE 1=1
";

$params = [];
if ($turma_id) {
    $sql .= " AND p.turma_id = ?";
    $params[] = $turma_id;
}
if ($busca) {
    $sql .= " AND p.nome LIKE ?";
    $params[] = "%$busca%";
}
$sql .= " ORDER BY p.nome";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nomeTurma = null;
if ($turma_id) {
    $stmtTurma = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
    $stmtTurma->execute([$turma_id]);
    $nomeTurma = $stmtTurma->fetchColumn();
}
?>

<style>
    .table-responsive { overflow: visible !important; }
    .badge-passagens { background-color: var(--ser-blue); }
    .btn-search { background-color: var(--ser-blue); color: white; }
    .btn-search:hover { background-color: #2a6da3; color: white; }
    .card-filter { background-color: #f8f9fa; border: 1px solid #e9ecef; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-people-fill me-2" style="color: var(--ser-blue);"></i>Participantes
            </h3>
            <?php if ($nomeTurma): ?>
                <span class="badge bg-info text-dark mt-1">Turma: <?= htmlspecialchars($nomeTurma) ?></span>
            <?php endif; ?>
        </div>
        <a href="participantes_cadastrar.php" class="btn btn-success shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Novo Participante
        </a>
    </div>

    <div class="card card-filter border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="turma_id" value="<?= htmlspecialchars($turma_id ?? '') ?>">
                
                <div class="col-md-6">
                    <label class="small fw-bold text-muted mb-1">BUSCAR POR NOME</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="busca" class="form-control border-start-0" 
                               placeholder="Digite o nome do participante..." value="<?= htmlspecialchars($busca ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-search w-100">
                        Filtrar Resultados
                    </button>
                </div>
                
                <div class="col-md-3">
                    <a href="participantes_lista.php" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-eraser me-1"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nome</th>
                            <th>Nº Processo</th>
                            <th class="text-center">Passagens</th>
                            <th>Turma</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participantes as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($p['nome']) ?></div>
                                <div class="small text-muted" style="font-size: 0.75rem; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars($p['observacoes']) ?>
                                </div>
                            </td>
                            <td><code class="text-secondary"><?= htmlspecialchars($p['numero_processo']) ?></code></td>
                            <td class="text-center">
                                <span class="badge rounded-pill badge-passagens px-3">
                                    <?= $p['total_passagens'] ?? 0 ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border fw-normal">
                                    <i class="bi bi-tag me-1"></i><?= htmlspecialchars($p['turma']) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="participantes_detalhes.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="participantes_editar.php?id=<?= $p['id'] ?>&turma_id=<?= $turma_id ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="participantes_excluir.php?id=<?= $p['id'] ?>&turma_id=<?= $turma_id ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Deseja excluir este participante?')" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (count($participantes) === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-exclamation fs-1 d-block mb-2 opacity-25"></i>
                                Nenhum participante encontrado.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-5 d-flex justify-content-between">
        <a href="turmas_lista.php" class="btn btn-link text-decoration-none p-0 text-secondary">
            <i class="bi bi-arrow-left"></i> Voltar para Turmas
        </a>
        <?php if ($turma_id): ?>
            <a href="participantes_lista.php" class="btn btn-sm btn-light border text-secondary text-decoration-none">
                <i class="bi bi-people me-1"></i> Ver Todos os Participantes
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>