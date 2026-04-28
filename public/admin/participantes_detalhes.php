<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

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

// Verificar fichas
$stmt = $pdo->prepare("SELECT id FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$id]);
$inclusao = $stmt->fetch();

$stmt = $pdo->prepare("SELECT id FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$id]);
$final = $stmt->fetch();

// Função helper
function campo($valor) {
    return $valor ? htmlspecialchars($valor) : '<span class="text-muted">Não informado</span>';
}
?>

<div class="container mt-4">

    <h3>👤 Detalhes do Participante</h3>

    <div class="card mb-4 shadow">
        <div class="card-body">

            <h5 class="mb-3"><?= htmlspecialchars($p['nome']) ?></h5>

            <div class="row">

                <div class="col-md-4 mb-2">
                    <strong>Nº Processo:</strong><br>
                    <?= campo($p['numero_processo']) ?>
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Turma:</strong><br>
                    <?= campo($p['turma_nome']) ?>
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Total de Passagens:</strong><br>
                    <?= campo($p['total_passagens']) ?>
                </div>

            </div>

            <?php if (!empty($p['observacoes'])): ?>
                <hr>
                <strong>Observações:</strong>
                <p><?= nl2br(htmlspecialchars($p['observacoes'])) ?></p>
            <?php endif; ?>

        </div>
    </div>

    <!-- 🔘 AÇÕES -->
    <div class="card shadow">
        <div class="card-body">

            <h5 class="mb-3">Ações</h5>

            <div class="d-flex gap-2 flex-wrap">

                <!-- FICHA INCLUSÃO -->
                <?php if (!$inclusao): ?>
                    <a href="ficha_inclusao.php?participante_id=<?= $p['id'] ?>"
                        class="btn btn-primary">
                        ➕ Preencher Ficha de Inclusão
                    </a>
                <?php else: ?>
                    <a href="ficha_inclusao_ver.php?participante_id=<?= $p['id'] ?>"
                        class="btn btn-outline-primary">
                        👁 Ver Ficha de Inclusão
                    </a>
                <?php endif; ?>

                <!-- FICHA FINAL -->
                <?php if (!$inclusao): ?>
                    <button class="btn btn-secondary" disabled>
                        ⚠ Preencha a inclusão primeiro
                    </button>
                <?php elseif (!$final): ?>
                    <a href="ficha_final_form.php?participante_id=<?= $p['id'] ?>"
                        class="btn btn-dark">
                        📝 Preencher Ficha Final
                    </a>
                <?php else: ?>
                    <a href="ficha_final_ver.php?participante_id=<?= $p['id'] ?>"
                        class="btn btn-outline-dark">
                        👁 Ver Ficha Final
                    </a>
                <?php endif; ?>

            </div>

        </div>
    </div>

    <a href="participantes_lista.php" class="btn btn-secondary mt-3">
        ← Voltar
    </a>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>