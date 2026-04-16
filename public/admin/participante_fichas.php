<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../layout/admin_header.php';

auth();

$id = $_GET['id'] ?? null;

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

/**
 * Função padrão para exibir campos
 */
function campo($array, $campo)
{
    return (!empty($array[$campo]))
        ? htmlspecialchars($array[$campo])
        : '<span class="text-muted">Não informado</span>';
}
?>

<div class="container mt-4">

    <h3>📄 Fichas do Participante</h3>
    <p><strong><?= htmlspecialchars($p['nome']) ?></strong></p>

    <hr>

    <!-- 🔴 FICHA INCLUSÃO -->
    <?php if ($inclusao): ?>
        <div class="card mb-4 border-danger">
            <div class="card-body">

                <h5 class="text-danger">Ficha de Inclusão</h5>

                <p><strong>Cor:</strong> <?= campo($inclusao, 'cor') ?></p>
                <p><strong>Situação Civil:</strong> <?= campo($inclusao, 'situacao_civil') ?></p>
                <p><strong>Religião:</strong> <?= campo($inclusao, 'religiao') ?></p>
                <p><strong>Escolaridade:</strong> <?= campo($inclusao, 'escolaridade') ?></p>
                <p><strong>Renda:</strong> <?= campo($inclusao, 'renda_familiar') ?></p>
                <p><strong>Profissão:</strong> <?= campo($inclusao, 'profissao') ?></p>
                <p><strong>Saúde:</strong> <?= campo($inclusao, 'problemas_saude') ?></p>
                <p><strong>Uso de Álcool:</strong> <?= campo($inclusao, 'uso_alcool') ?></p>
                <p><strong>Drogas:</strong> <?= campo($inclusao, 'drogas_utilizadas') ?></p>
                <p><strong>Violência Praticada:</strong> <?= campo($inclusao, 'violencia_praticada') ?></p>
                <p><strong>Violência Sofrida:</strong> <?= campo($inclusao, 'violencia_sofrida') ?></p>
                <p><strong>Histórico Familiar:</strong> <?= campo($inclusao, 'historico_familiar') ?></p>
                <p><strong>Situação Jurídica:</strong> <?= campo($inclusao, 'situacao_juridica') ?></p>
                <p><strong>Expectativa:</strong> <?= campo($inclusao, 'expectativa_grupo') ?></p>

            </div>
        </div>
    <?php endif; ?>

    <!-- ⚫ FICHA FINAL -->
    <?php if ($final): ?>
        <div class="card border-dark">
            <div class="card-body">

                <h5>Ficha de Avaliação Final</h5>

                <p><strong>Sentimento ao denunciar:</strong> <?= campo($final, 'sentimento_denuncia') ?></p>
                <p><strong>Acha a denúncia justa?</strong> <?= campo($final, 'acha_justa') ?></p>
                <p><strong>Motivo da denúncia:</strong> <?= campo($final, 'motivo_denuncia') ?></p>
                <p><strong>Teve dificuldade para participar?</strong> <?= campo($final, 'dificuldade_participar') ?></p>
                <p><strong>Motivo da dificuldade:</strong> <?= campo($final, 'motivo_dificuldade') ?></p>
                <p><strong>Avaliação da participação:</strong> <?= campo($final, 'avaliacao_participacao') ?></p>
                <p><strong>Sentimento no início:</strong> <?= campo($final, 'sentimento_inicio') ?></p>
                <p><strong>Outro sentimento:</strong> <?= campo($final, 'outro_sentimento') ?></p>
                <p><strong>Pontos positivos:</strong> <?= campo($final, 'pontos_positivos') ?></p>
                <p><strong>Pontos negativos:</strong> <?= campo($final, 'pontos_negativos') ?></p>
                <p><strong>Temas importantes:</strong> <?= campo($final, 'temas_importantes') ?></p>
                <p><strong>Houve mudança?</strong> <?= campo($final, 'houve_mudanca') ?></p>
                <p><strong>Descrição da mudança:</strong> <?= campo($final, 'descricao_mudanca') ?></p>
                <p><strong>Gostou do grupo?</strong> <?= campo($final, 'gostou_grupo') ?></p>
                <p><strong>Impacto nos relacionamentos:</strong> <?= campo($final, 'impacto_relacionamentos') ?></p>
                <p><strong>Motivo do impacto:</strong> <?= campo($final, 'motivo_impacto') ?></p>
                <p><strong>Mudou pensamento?</strong> <?= campo($final, 'mudou_pensamento') ?></p>
                <p><strong>Explicação do pensamento:</strong> <?= campo($final, 'explicacao_pensamento') ?></p>
                <p><strong>Recomendaria?</strong> <?= campo($final, 'recomendaria') ?></p>
                <p><strong>Motivo da recomendação:</strong> <?= campo($final, 'motivo_recomendacao') ?></p>

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