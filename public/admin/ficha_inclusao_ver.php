<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();

$participante_id = $_GET['participante_id'] ?? null;
if (!$participante_id) die("Participante não informado");

// participante
$stmt = $pdo->prepare("SELECT nome, numero_processo FROM participantes WHERE id = ?");
$stmt->execute([$participante_id]);
$p = $stmt->fetch();

// ficha
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$participante_id]);
$f = $stmt->fetch();

function campo($f, $c) {
    return !empty($f[$c]) ? htmlspecialchars($f[$c]) : '<span class="text-muted">Não informado</span>';
}
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Ficha de Inclusão</h3>

        <div>
            <a href="gerar_pdf_inclusao.php?participante_id=<?= $participante_id ?>" 
               class="btn btn-danger">
               📄 Gerar PDF
            </a>

            <a href="participantes_detalhes.php?id=<?= $participante_id ?>" 
               class="btn btn-secondary">
               ← Voltar
            </a>
        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-body">
            <h5><?= htmlspecialchars($p['nome']) ?></h5>
            <p><strong>Nº Processo:</strong> <?= campo($p,'numero_processo') ?></p>
        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-header bg-primary text-white">Dados Gerais</div>
        <div class="card-body">
            <p><strong>Cor:</strong> <?= campo($f,'cor') ?></p>
            <p><strong>Religião:</strong> <?= campo($f,'religiao') ?></p>
            <p><strong>Escolaridade:</strong> <?= campo($f,'escolaridade') ?></p>
            <p><strong>Renda:</strong> <?= campo($f,'renda_familiar') ?></p>
            <p><strong>Trabalho:</strong> <?= campo($f,'trabalho') ?></p>
            <p><strong>Profissão:</strong> <?= campo($f,'profissao') ?></p>
            <p><strong>Moradia:</strong> <?= campo($f,'condicao_moradia') ?></p>
        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-header bg-success text-white">Saúde</div>
        <div class="card-body">
            <p><strong>Problemas:</strong> <?= campo($f,'problemas_saude') ?></p>
            <p><strong>Medicação:</strong> <?= campo($f,'uso_medicacao') ?></p>
            <p><strong>Álcool:</strong> <?= campo($f,'uso_alcool') ?></p>
            <p><strong>Drogas:</strong> <?= campo($f,'drogas_utilizadas') ?></p>
        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-header bg-warning">Histórico</div>
        <div class="card-body">
            <p><strong>Violência praticada:</strong> <?= campo($f,'violencia_praticada') ?></p>
            <p><strong>Violência sofrida:</strong> <?= campo($f,'violencia_sofrida') ?></p>
            <p><strong>Histórico familiar:</strong> <?= campo($f,'historico_familiar') ?></p>
            <p><strong>Situação jurídica:</strong> <?= campo($f,'situacao_juridica') ?></p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">Expectativa</div>
        <div class="card-body">
            <?= campo($f,'expectativa_grupo') ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>   