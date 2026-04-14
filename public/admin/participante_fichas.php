<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/funcoes.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();

$id = $_GET['id'] ?? null;
$ficha = $ficha ?? [];

if (!$id) die("Participante não informado");

// Buscar participante
$stmt = $pdo->prepare("SELECT * FROM participantes WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) die("Participante não encontrado");

// Buscar fichas
$stmt = $pdo->prepare("SELECT * FROM ficha_inclusao WHERE participante_id = ?");
$stmt->execute([$id]);
$inclusao = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$id]);
$final = $stmt->fetch();
?>

<div class="container mt-4">

    <h3>📄 Fichas do Participante</h3>
    <p><strong><?= htmlspecialchars($p['nome']) ?></strong></p>

    <hr>

    <!-- 🔴 FICHA INCLUSÃO -->
    <?php if ($inclusao): ?>
        <div class="card mb-4 border-danger">
            <div class="card-body">

                <h5 class="text-danger">Ficha de Inclusão (Vítima)</h5>

                <p><strong>Cor:</strong> <?= campo($inclusao, 'cor') ?></p>
                <p><strong>Situação Civil:</strong> <?= campo($ficha, 'situacao_civil') ?></p>
                <p><strong>Religião:</strong> <?= campo($ficha, 'religiao') ?></p>
                <p><strong>Escolaridade:</strong> <?= campo($ficha, 'escolaridade') ?></p>
                <p><strong>Renda:</strong> <?= campo($ficha, 'renda_familiar') ?></p>
                <p><strong>Profissão:</strong> <?= campo($ficha, 'profissao') ?></p>
                <p><strong>Saúde:</strong> <?= campo($ficha, 'problemas_saude') ?></p>
                <p><strong>Uso de Álcool:</strong> <?= campo($ficha, 'uso_alcool') ?></p>
                <p><strong>Drogas:</strong> <?= campo($ficha, 'drogas_utilizadas') ?></p>
                <p><strong>Violência Praticada:</strong> <?= campo($ficha, 'violencia_praticada') ?></p>
                <p><strong>Violência Sofrida:</strong> <?= campo($ficha, 'violencia_sofrida') ?></p>
                <p><strong>Histórico Familiar:</strong> <?= campo($ficha, 'historico_familiar') ?></p>
                <p><strong>Situação Jurídica:</strong> <?= campo($ficha, 'situacao_juridica') ?></p>
                <p><strong>Expectativa:</strong> <?= campo($ficha, 'expectativa_grupo') ?></p>

            </div>
        </div>
    <?php endif; ?>

    <!-- ⚫ FICHA FINAL -->
    <?php if ($final): ?>
        <div class="card border-dark">
            <div class="card-body">

                <h5>Ficha de Avaliação Final</h5>

                <p><strong>Sentimento ao denunciar:</strong>
                    <?= mostrarCampo($ficha, 'sentimento_denuncia') ?>
                </p>

                <p><strong>Acha a denúncia justa?</strong>
                    <?= mostrarCampo($ficha, 'acha_justa') ?>
                </p>

                <p><strong>Motivo da denúncia:</strong>
                    <?= mostrarCampo($ficha, 'motivo_denuncia') ?>
                </p>

                <p><strong>Teve dificuldade para participar?</strong>
                    <?= mostrarCampo($ficha, 'dificuldade_participar') ?>
                </p>

                <p><strong>Motivo da dificuldade:</strong>
                    <?= mostrarCampo($ficha, 'motivo_dificuldade') ?>
                </p>

                <p><strong>Avaliação da participação:</strong>
                    <?= mostrarCampo($ficha, 'avaliacao_participacao') ?>
                </p>

                <p><strong>Sentimento no início:</strong>
                    <?= mostrarCampo($ficha, 'sentimento_inicio') ?>
                </p>

                <p><strong>Outro sentimento:</strong>
                    <?= mostrarCampo($ficha, 'outro_sentimento') ?>
                </p>

                <p><strong>Pontos positivos:</strong>
                    <?= mostrarCampo($ficha, 'pontos_positivos') ?>
                </p>

                <p><strong>Pontos negativos:</strong>
                    <?= mostrarCampo($ficha, 'pontos_negativos') ?>
                </p>

                <p><strong>Temas importantes:</strong>
                    <?= mostrarCampo($ficha, 'temas_importantes') ?>
                </p>

                <p><strong>Houve mudança?</strong>
                    <?= mostrarCampo($ficha, 'houve_mudanca') ?>
                </p>

                <p><strong>Descrição da mudança:</strong>
                    <?= mostrarCampo($ficha, 'descricao_mudanca') ?>
                </p>

                <p><strong>Gostou do grupo?</strong>
                    <?= mostrarCampo($ficha, 'gostou_grupo') ?>
                </p>

                <p><strong>Impacto nos relacionamentos:</strong>
                    <?= mostrarCampo($ficha, 'impacto_relacionamentos') ?>
                </p>

                <p><strong>Motivo do impacto:</strong>
                    <?= mostrarCampo($ficha, 'motivo_impacto') ?>
                </p>

                <p><strong>Mudou pensamento?</strong>
                    <?= mostrarCampo($ficha, 'mudou_pensamento') ?>
                </p>

                <p><strong>Explicação do pensamento:</strong>
                    <?= mostrarCampo($ficha, 'explicacao_pensamento') ?>
                </p>

                <p><strong>Recomendaria?</strong>
                    <?= mostrarCampo($ficha, 'recomendaria') ?>
                </p>

                <p><strong>Motivo da recomendação:</strong>
                    <?= mostrarCampo($ficha, 'motivo_recomendacao') ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!$inclusao && !$final): ?>
        <div class="alert alert-warning">
            Nenhuma ficha encontrada para este participante.
        </div>
    <?php endif; ?>

</div>

<a href="participantes_lista.php" class="btn btn-secondary mb-3">
    ← Voltar
</a>

<?php require_once __DIR__ . '/../../layout/admin_footer.php'; ?>