<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$turma_id = $_GET['turma_id'] ?? null;

if (!$turma_id) {
    die("Turma não informada");
}

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$turma_id]);
$turma = $stmt->fetch();

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

<div class="container mt-4">

    <h3 class="mb-3">📅 Sessões - <?= htmlspecialchars($turma['nome']) ?></h3>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">
            Presenças registradas com sucesso!
        </div>
    <?php endif; ?>

    <a href="sessao_criar.php?turma_id=<?= $turma_id ?>" class="btn btn-success mb-3">
        ➕ Nova Sessão
    </a>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row g-4">

                <?php if (count($sessoes) > 0): ?>
                    <?php foreach ($sessoes as $s): ?>

                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm border-0 h-100">

                                <div class="card-body d-flex flex-column">

                                    <!-- 📅 DATA -->
                                    <div class="mb-2 text-muted small">
                                        📅 <?= date('d/m/Y', strtotime($s['data'])) ?>
                                    </div>

                                    <!-- 📝 DESCRIÇÃO -->
                                    <h5 class="fw-bold mb-3">
                                        <?= htmlspecialchars($s['descricao']) ?: 'Sem descrição' ?>
                                    </h5>

                                    <!-- 👥 PRESENÇA -->
                                    <div class="mb-3">
                                        <span class="badge bg-success">
                                            👥 <?= $s['presentes'] ?> presentes
                                        </span>
                                    </div>

                                    <!-- 🔘 AÇÕES -->
                                    <div class="mt-auto d-flex flex-wrap gap-2">

                                        <a href="sessao_presenca.php?sessao_id=<?= $s['id'] ?>"
                                            class="btn btn-primary btn-sm w-100">
                                            👥 Registrar / Editar Presença
                                        </a>

                                        <div class="d-flex gap-2 w-100">

                                            <a href="sessao_editar.php?id=<?= $s['id'] ?>"
                                                class="btn btn-warning btn-sm w-50">
                                                ✏️ Editar
                                            </a>

                                            <a href="sessao_excluir.php?id=<?= $s['id'] ?>&turma_id=<?= $turma_id ?>"
                                                class="btn btn-danger btn-sm w-50"
                                                onclick="return confirm('Deseja excluir esta sessão?')">
                                                ❌ Excluir
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>

                    <div class="col-12">
                        <div class="alert alert-secondary text-center">
                            Nenhuma sessão cadastrada.
                        </div>
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>

    <a href="turmas_lista.php" class="btn btn-secondary mt-3">
    Voltar
</a>

</div>

<?php require_once __DIR__ . '/../layout/admin_footer.php'; ?>