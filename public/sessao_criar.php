<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

// Busca o nome da turma para exibir no título (melhora o contexto)
$stmt_turma = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
$stmt_turma->execute([$turma_id]);
$turma = $stmt_turma->fetch();

if (!$turma) {
    die("Turma não encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO turmas_sessoes (turma_id, data, descricao)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $_POST['turma_id'],
        $_POST['data'],
        $_POST['descricao']
    ]);

    header("Location: sessoes_lista.php?turma_id=" . $_POST['turma_id']);
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <div class="mb-4">
                <h3 class="fw-bold text-dark">
                    <i class="bi bi-calendar-plus me-2 text-success"></i>Agendar Nova Sessão
                </h3>
                <p class="text-muted">Turma: <strong><?= htmlspecialchars($turma['nome']) ?></strong></p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="hidden" name="turma_id" value="<?= $turma_id ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Data do Encontro</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="data" class="form-control" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <small class="text-muted">Escolha a data em que o grupo irá se reunir.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Título ou Tema da Sessão</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-chat-left-text"></i></span>
                                <input type="text" name="descricao" class="form-control" 
                                       placeholder="Ex: 1º Encontro - Introdução e Gênero" required>
                            </div>
                            <small class="text-muted">Descreva brevemente o tema que será abordado.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="sessoes_lista.php?turma_id=<?= $turma_id ?>" class="btn btn-light border">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-1"></i> Criar Sessão
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <div class="alert alert-info d-inline-block py-2 px-3 small shadow-sm">
                    <i class="bi bi-info-circle me-2"></i>
                    Após criar a sessão, você poderá registrar as presenças.
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>