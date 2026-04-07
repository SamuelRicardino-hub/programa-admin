<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();
canAny(['admin', 'atendente']);

$caso_id = $_GET['id'] ?? null;

if (!$caso_id) {
    die("Caso não informado");
}

// ==============================
// 🔍 BUSCAR CASO
// ==============================
$stmt = $pdo->prepare("SELECT * FROM casos WHERE id = ?");
$stmt->execute([$caso_id]);
$caso = $stmt->fetch();

if (!$caso) {
    die("Caso não encontrado");
}

// ==============================
// 👥 BUSCAR PARTICIPANTES DO CASO
// ==============================
$stmt = $pdo->prepare("
    SELECT * FROM participantes 
    WHERE caso_id = ?
");
$stmt->execute([$caso_id]);
$participantes = $stmt->fetchAll();

$vitima = null;
$autor = null;

foreach ($participantes as $p) {
    if ($p['tipo'] == 'vitima') {
        $vitima = $p;
    } elseif ($p['tipo'] == 'autor') {
        $autor = $p;
    }
}

// ==============================
// 📄 FICHA INCLUSÃO (VÍTIMA)
// ==============================
$fichaInclusao = null;

if ($vitima) {
    $stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
    $stmt->execute([$vitima['id']]);
    $fichaInclusao = $stmt->fetch();
}

// ==============================
// 📄 FICHA FINAL (AUTOR)
// ==============================
$fichaFinal = null;

if ($autor) {
    $stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
    $stmt->execute([$autor['id']]);
    $fichaFinal = $stmt->fetch();
}

require_once __DIR__ . '/../../layout/admin_header.php';
?>

<div class="container mt-4">

    <h2>Detalhes do Caso #<?= $caso_id ?></h2>

    <!-- STATUS -->
    <div class="mb-3">
        <span class="badge bg-primary">
            Status: <?= $caso['status'] ?? 'ativo' ?>
        </span>
    </div>

    <!-- 👤 VÍTIMA -->
    <div class="card mb-3">
        <div class="card-header bg-danger text-white">
            Vítima
        </div>
        <div class="card-body">

            <?php if ($vitima): ?>
                <p><strong>Nome:</strong> <?= $vitima['nome'] ?></p>
                <p><strong>CPF:</strong> <?= $vitima['cpf'] ?></p>
                <p><strong>Telefone:</strong> <?= $vitima['telefone'] ?></p>

                <?php if ($fichaInclusao): ?>
                    <span class="badge bg-success">Ficha de inclusão preenchida</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Sem ficha de inclusão</span>
                <?php endif; ?>

            <?php else: ?>
                <p class="text-muted">Nenhuma vítima vinculada</p>
            <?php endif; ?>

        </div>
    </div>

    <!-- 👤 AUTOR -->
    <div class="card mb-3">
        <div class="card-header bg-dark text-white">
            Autor
        </div>
        <div class="card-body">

            <?php if ($autor): ?>
                <p><strong>Nome:</strong> <?= $autor['nome'] ?></p>
                <p><strong>CPF:</strong> <?= $autor['cpf'] ?></p>
                <p><strong>Telefone:</strong> <?= $autor['telefone'] ?></p>

                <?php if ($fichaFinal): ?>
                    <span class="badge bg-success">Ficha final preenchida</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Sem ficha final</span>
                <?php endif; ?>

            <?php else: ?>
                <p class="text-muted">Nenhum autor vinculado</p>
            <?php endif; ?>

        </div>
    </div>

    <!-- 📄 AÇÕES -->
    <div class="mt-3">

        <a href="relatorio_caso.php?id=<?= $caso_id ?>" 
           target="_blank"
           class="btn btn-danger">
            📄 Gerar PDF do Caso
        </a>

        <a href="casos_lista.php" class="btn btn-secondary">
            Voltar
        </a>

    </div>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>