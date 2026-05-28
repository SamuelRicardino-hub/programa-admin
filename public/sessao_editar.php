<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Sessão não informada");
}

// Buscar sessão com join para ter o nome da turma
$stmt = $pdo->prepare("
    SELECT ts.*, t.nome as turma_nome
    FROM turmas_sessoes ts
    JOIN turmas t ON t.id = ts.turma_id
    WHERE ts.id = ?
");
$stmt->execute([$id]);
$sessao = $stmt->fetch();

if (!$sessao) {
    die("Sessão não encontrada");
}

// ==============================
// 💾 PROCESSAR ATUALIZAÇÃO
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST['data'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    if (!$data) {
        $erro = "A data da sessão é obrigatória.";
    } else {
        $stmt = $pdo->prepare("
            UPDATE turmas_sessoes
            SET data = ?, descricao = ?
            WHERE id = ?
        ");
        $stmt->execute([$data, $descricao, $id]);

        // REGISTRO DE LOG
        registrarLog(
            $pdo,
            'UPDATE',
            'turmas_sessoes',
            $id,
            "Editou sessão ID $id da turma " . $sessao['turma_nome'],
            $_SESSION['usuario']['id']
        );

        header("Location: sessoes_lista.php?turma_id=" . $sessao['turma_id'] . "&editado=1");
        exit;
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar Sessão
                    </h3>
                    <p class="text-muted mb-0">Alterando dados do encontro da turma: <strong><?= htmlspecialchars($sessao['turma_nome']) ?></strong></p>
                </div>
                <a href="sessoes_lista.php?turma_id=<?= $sessao['turma_id'] ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Cancelar
                </a>
            </div>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i> <?= $erro ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Data da Sessão</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                                <input type="date" name="data" class="form-control form-control-lg"
                                    value="<?= $sessao['data'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Descrição ou Tema do Encontro</label>
                            <textarea name="descricao" class="form-control" rows="4" 
                                placeholder="Descreva os temas abordados ou observações gerais sobre esta sessão..."><?= htmlspecialchars($sessao['descricao']) ?></textarea>
                            <div class="form-text mt-2">
                                Forneça detalhes que ajudem a identificar o conteúdo deste encontro futuramente.
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="sessoes_lista.php?turma_id=<?= $sessao['turma_id'] ?>" class="btn btn-light border px-4">
                                Voltar
                            </a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold">
                                <i class="bi bi-save me-2"></i>Atualizar Sessão
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="mt-4 card bg-light border-0">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bi bi-info-circle me-3 fs-4"></i>
                        <div>
                            Ao alterar a data, os registros de <strong>presença</strong> vinculados a este dia permanecerão salvos, mas passarão a ser referenciados pela nova data escolhida.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>