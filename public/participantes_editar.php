<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../layout/admin_header.php";

auth();
canAny(['admin', 'atendente']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

// 1. BUSCA OS DADOS DO PARTICIPANTE (Incluindo a turma_id)
$stmt = $pdo->prepare("SELECT id, nome, numero_processo, observacoes, turma_id, total_passagens FROM participantes WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Participante não encontrado.");
}

// 2. BUSCA TODAS AS TURMAS PARA O SELECT
$stmtTurmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome ASC");
$turmas = $stmtTurmas->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">📝 Editar Participante</h3>
            <p class="text-muted">ID: #<?= $p['id'] ?></p>
        </div>
        <a href="participantes_detalhes.php?id=<?= $p['id'] ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="participantes_salvar.php" method="POST">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control form-control-lg"
                            value="<?= htmlspecialchars($p['nome']) ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Nº do Caso / Processo</label>
                        <input type="text" name="numero_processo" class="form-control form-control-lg"
                            value="<?= htmlspecialchars($p['numero_processo'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-bold">Passagens</label>
                        <input type="number" name="total_passagens" class="form-control form-control-lg" value="<?= $p['total_passagens'] ?? 0 ?>" min="0">
                    </div>


                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Turma</label>
                        <select name="turma_id" class="form-select form-select-lg" required>
                            <option value="">Selecione uma turma...</option>
                            <?php foreach ($turmas as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= ($t['id'] == $p['turma_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Observações Gerais</label>
                    <textarea name="observacoes" class="form-control" rows="5"
                        placeholder="Anote aqui informações relevantes..."><?= htmlspecialchars($p['observacoes'] ?? '') ?></textarea>
                </div>

                <hr>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-check-circle me-2"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../layout/admin_footer.php"; ?>