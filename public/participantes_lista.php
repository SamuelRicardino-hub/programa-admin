<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . '/../config/funcoes.php';
require_once __DIR__ . "/../layout/admin_header.php";
require_once __DIR__ . '/../config/auth.php';
auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;
$busca    = $_GET['busca'] ?? null;

$sql = "
    SELECT p.id,
     p.nome,
     p.numero_processo,
     p.total_passagens,
     p.observacoes,
     t.nome AS turma
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

if ($turma_id) {
    $stmtTurma = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
    $stmtTurma->execute([$turma_id]);
    $nomeTurma = $stmtTurma->fetchColumn();
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Participantes</h3>
</div>
<?php if ($turma_id && $nomeTurma): ?>
    <div class="alert alert-info mt-3">
        Mostrando participantes da turma:
        <strong><?= htmlspecialchars($nomeTurma) ?></strong>
        <a href="participantes_lista.php" class="float-end">
            Ver todos
        </a>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-2">

                    <input type="hidden" name="turma_id"
                        value="<?= htmlspecialchars($turma_id ?? '') ?>">

                    <div class="col-md-6">
                        <input type="text"
                            name="busca"
                            class="form-control"
                            placeholder="Buscar participante"
                            value="<?= htmlspecialchars($busca ?? '') ?>">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Buscar
                        </button>
                    </div>

                    <div class="col-md-2">
                        <a href="participantes_lista.php" class="btn btn-secondary w-100">
                            Limpar
                        </a>
                    </div>

                </form>

            </div>

            <a href="participantes_cadastrar.php" class="btn btn-primary px-4">
                <i class="bi bi-person-plus me-1"></i> Novo Participante
            </a>

        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Número do Processo</th>
                        <th>Passagens</th>
                        <th>Observações</th>
                        <th>Turma</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td><?= htmlspecialchars($p['numero_processo']) ?></td>
                            <td><span class="badge bg-primary">
                                    <?= $p['total_passagens'] ?? 0 ?>
                                </span></td>
                            <td><?= htmlspecialchars($p['observacoes']) ?></td>
                            <td><?= htmlspecialchars($p['turma']) ?></td>
                            <td class="text-center">

                                <a href="participantes_editar.php?id=<?= $p['id'] ?>&turma_id=<?= $turma_id ?>"
                                    class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <a href="participantes_excluir.php?id=<?= $p['id'] ?>&turma_id=<?= $turma_id ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Deseja excluir este participante?')">
                                    Excluir
                                </a>

                                <a href="participantes_detalhes.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info">
                                    Ver
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (count($participantes) === 0): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Nenhum participante cadastrado
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<a href="turmas_lista.php" class="btn btn-secondary mt-3">
    Voltar
</a>
<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>