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

// 1. BUSCA OS DADOS DO PARTICIPANTE
$stmt = $pdo->prepare("SELECT id, nome, numero_processo, observacoes, turma_id, total_passagens FROM participantes WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Participante não encontrado.");
}

// 2. BUSCA TODAS AS TURMAS PARA O SELECT
$stmtTurmas = $pdo->query("SELECT id, nome, status FROM turmas ORDER BY nome ASC");
$turmas = $stmtTurmas->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-pencil-square me-2" style="color: var(--ser-orange);"></i>Editar Cadastro
            </h3>
            <p class="text-muted small mb-0">Atualize as informações do prontuário #<?= $p['id'] ?></p>
        </div>
        <a href="participantes_detalhes.php?id=<?= $p['id'] ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar aos Detalhes
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="participantes_salvar.php" method="POST">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Nome Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nome" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($p['nome']) ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Total de Passagens</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-ticket-perforated"></i></span>
                                    <input type="number" name="total_passagens" class="form-control border-start-0 text-center" 
                                           value="<?= $p['total_passagens'] ?? 0 ?>" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Nº do Caso / Processo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-hash"></i></span>
                                    <input type="text" name="numero_processo" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($p['numero_processo'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Turma Vinculada</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-mortarboard"></i></span>
                                    <select name="turma_id" class="form-select border-start-0" required>
                                        <option value="">Selecione uma turma...</option>
                                        <?php foreach ($turmas as $t): ?>
                                            <option value="<?= $t['id'] ?>" <?= ($t['id'] == $p['turma_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($t['nome']) ?> 
                                                <?= (strtolower(trim($t['status'])) !== 'ativa' && strtolower(trim($t['status'])) !== 'ativo') ? '(Inativa)' : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Observações Gerais</label>
                            <textarea name="observacoes" class="form-control" rows="4" 
                                      placeholder="Anote aqui informações relevantes..."><?= htmlspecialchars($p['observacoes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                            <a href="participantes_lista.php" class="text-muted text-decoration-none small">
                                <i class="bi bi-x-circle"></i> Cancelar e Sair
                            </a>

                            <button type="submit" class="btn px-5 shadow-sm" style="background-color: var(--ser-blue); color: white;">
                                <i class="bi bi-cloud-check me-2"></i> Atualizar Prontuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../layout/admin_footer.php"; ?>