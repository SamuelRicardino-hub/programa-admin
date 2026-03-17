<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../layout/admin_header.php";
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

$turmas = $pdo->query("
    SELECT id, nome 
    FROM turmas 
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$participantes = [];
$nomeTurma = null;

if ($turma_id) {

    $stmtTurma = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
    $stmtTurma->execute([$turma_id]);
    $nomeTurma = $stmtTurma->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT nome, cpf, telefone, email, idade
        FROM participantes
        WHERE turma_id = ?
        ORDER BY nome
    ");
    $stmt->execute([$turma_id]);
    $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2 class="mb-4">Participantes por Turma</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <form method="GET">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Selecione a Turma</label>
                    <select name="turma_id" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php foreach ($turmas as $t): ?>
                            <option value="<?= $t['id'] ?>"
                                <?= ($turma_id == $t['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<?php if ($turma_id && $nomeTurma): ?>

<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">
            Turma: <strong><?= htmlspecialchars($nomeTurma) ?></strong>
        </h5>

        <p>Total de participantes: 
            <strong><?= count($participantes) ?></strong>
        </p>

        <?php if (count($participantes) === 0): ?>
            <p class="text-muted">Nenhum participante nesta turma.</p>
        <?php else: ?>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Idade</th>
                        <th>Telefone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td><?= htmlspecialchars($p['cpf']) ?></td>
                            <td><?= htmlspecialchars($p['idade']) ?></td>
                            <td><?= htmlspecialchars($p['telefone']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</div>

<?php endif; ?>

<a href="participantes_lista.php" class="btn btn-secondary mt-3">
    Voltar
</a>

<?php require_once __DIR__ . "/../../layout/admin_footer.php"; ?>