<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$caso_id = $_GET['caso_id'] ?? null;

if (!$caso_id) {
    die("Caso não informado");
}

// 📌 Buscar caso
$stmt = $pdo->prepare("SELECT * FROM casos WHERE id = ?");
$stmt->execute([$caso_id]);
$caso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$caso) {
    die("Caso não encontrado");
}

// 📜 Buscar histórico
$stmt = $pdo->prepare("
    SELECT c.*, u.nome AS usuario_nome
    FROM casos_andamento c
    LEFT JOIN usuarios u ON u.id = c.usuario_id
    WHERE c.caso_id = ?
    ORDER BY c.criado_em DESC
");
$stmt->execute([$caso_id]);
$andamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <h3>Andamento do Caso #<?= $caso_id ?></h3>

    <!-- ➕ NOVO ANDAMENTO -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Registrar Andamento</h5>

            <form method="POST" action="caso_andamento_salvar.php">

                <input type="hidden" name="caso_id" value="<?= $caso_id ?>">

                <div class="mb-3">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" required></textarea>
                </div>

                <button class="btn btn-success">
                    Salvar andamento
                </button>
            </form>
        </div>
    </div>

    <!-- 📜 HISTÓRICO -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h5>Histórico</h5>

            <?php if (count($andamentos) > 0): ?>

                <div class="timeline">

                    <?php foreach ($andamentos as $a): ?>

                        <div class="timeline-item">

                            <div class="timeline-date">
                                <?= date('d/m/Y H:i', strtotime($a['criado_em'])) ?>
                            </div>

                            <div class="timeline-user">
                                <?= htmlspecialchars($a['usuario_nome'] ?? 'Sistema') ?>
                            </div>

                            <div class="timeline-content">
                                <?= nl2br(htmlspecialchars($a['descricao'])) ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <p>Nenhum andamento registrado.</p>

            <?php endif; ?>

        </div>
    </div>

    <a href="casos_detalhes.php?id=<?= $caso_id ?>" class="btn btn-secondary mt-3">
        Voltar
    </a>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>