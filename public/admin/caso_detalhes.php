<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: casos_lista.php");
    exit;
}

if (!$id) {
    die("Caso não informado");
}

$stmt = $pdo->prepare("SELECT status FROM casos WHERE id = ?");
$stmt->execute([$id]);
$caso = $stmt->fetch(PDO::FETCH_ASSOC);

// 👩 vítima
$sql = $pdo->prepare("
SELECT * FROM participantes 
WHERE caso_id = ? AND tipo = 'vitima'
");
$sql->execute([$id]);
$vitima = $sql->fetch(PDO::FETCH_ASSOC);

// 👨 autor
$sql = $pdo->prepare("
SELECT * FROM participantes 
WHERE caso_id = ? AND tipo = 'autor'
");
$sql->execute([$id]);
$autor = $sql->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <h3>Detalhes do Caso #<?= $id ?></h3>

    <?php if ($vitima): ?>
        <a href="ficha_inclusao.php?id=<?= $vitima['id'] ?>"
            class="btn btn-warning btn-sm mt-2">
            Ficha Inclusão
        </a>
    <?php endif; ?>

    <?php if ($vitima): ?>
        <a href="ficha_final.php?id=<?= $vitima['id'] ?>"
            class="btn btn-warning btn-sm mt-2">
            Ficha Final
        </a>
    <?php endif; ?>

    <a href="relatorio_caso.php?caso_id=<?= $id ?>"
        class="btn btn-sm btn-danger mb-3">
        Gerar PDF Completo
    </a>

    <div class="row">

        <!-- VÍTIMA -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5>Vítima</h5>

                    <?php if ($vitima): ?>

                        <p><strong>Nome:</strong> <?= htmlspecialchars($vitima['nome']) ?>
                        <p><strong>CPF:</strong> <?= htmlspecialchars($vitima['cpf']) ?></p>

                        <a href="participantes_detalhes.php?id=<?= $vitima['id'] ?>"
                            class="btn btn-secondary btn-sm">
                            Ver detalhes
                        </a>

                        <a href="caso_andamento.php?caso_id=<?= $id ?>"
                            class="btn btn-primary mb-3">
                            Ver Andamento
                        </a>

                    <?php else: ?>
                        <p>Não cadastrada</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- AUTOR -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5>Autor</h5>

                    <?php if ($autor): ?>

                        <p><strong>Nome:</strong> <?= htmlspecialchars($autor['nome']) ?></p>
                        <p><strong>CPF:</strong> <?= htmlspecialchars($autor['cpf']) ?></p>
                        <p>
                            <strong>Status:</strong>
                            <span class="badge <?= $caso['status'] == 'ativo' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($caso['status']) ?>
                            </span>
                        </p>

                        <a href="participantes_detalhes.php?id=<?= $autor['id'] ?>"
                            class="btn btn-secondary btn-sm">
                            Ver detalhes
                        </a>

                    <?php else: ?>

                        <p>Não cadastrado</p>

                        <a href="participante_novo.php?caso_id=<?= $id ?>&tipo=autor"
                            class="btn btn-primary btn-sm">
                            Cadastrar Autor
                        </a>

                    <?php endif; ?>

                </div>
            </div>
        </div>

        <?php if ($caso['status'] == 'ativo'): ?>
            <a href="caso_encerrar.php?id=<?= $id ?>"
                class="btn btn-success mt-3"
                onclick="return confirm('Deseja encerrar este caso?')">
                Encerrar Caso
            </a>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>