<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/logs.php';

auth();
canAny(['admin', 'atendente']);

function post($campo)
{
    return $_POST[$campo] ?? null;
}

$participante_id = post('participante_id');

if (!$participante_id) {
    die("Participante inválido");
}

$sql = $pdo->prepare("
INSERT INTO ficha_avaliacao_final (
    participante_id,
    sentimento_denuncia,
    acha_justa,
    motivo_denuncia,
    dificuldade_participar,
    motivo_dificuldade,
    avaliacao_participacao,
    pontos_positivos,
    pontos_negativos,
    temas_importantes,
    houve_mudanca,
    descricao_mudanca,
    impacto_relacionamentos,
    motivo_impacto,
    mudou_pensamento,
    explicacao_pensamento,
    recomendaria,
    motivo_recomendacao
) VALUES (
    :participante_id,
    :sentimento_denuncia,
    :acha_justa,
    :motivo_denuncia,
    :dificuldade_participar,
    :motivo_dificuldade,
    :avaliacao_participacao,
    :pontos_positivos,
    :pontos_negativos,
    :temas_importantes,
    :houve_mudanca,
    :descricao_mudanca,
    :impacto_relacionamentos,
    :motivo_impacto,
    :mudou_pensamento,
    :explicacao_pensamento,
    :recomendaria,
    :motivo_recomendacao
)");

$sql->execute([
    ':participante_id' => $participante_id,
    ':sentimento_denuncia' => post('sentimento_denuncia'),
    ':acha_justa' => post('acha_justa'),
    ':motivo_denuncia' => post('motivo_denuncia'),
    ':dificuldade_participar' => post('dificuldade_participar'),
    ':motivo_dificuldade' => post('motivo_dificuldade'),
    ':avaliacao_participacao' => post('avaliacao_participacao'),
    ':pontos_positivos' => post('pontos_positivos'),
    ':pontos_negativos' => post('pontos_negativos'),
    ':temas_importantes' => post('temas_importantes'),
    ':houve_mudanca' => post('houve_mudanca'),
    ':descricao_mudanca' => post('descricao_mudanca'),
    ':impacto_relacionamentos' => post('impacto_relacionamentos'),
    ':motivo_impacto' => post('motivo_impacto'),
    ':mudou_pensamento' => post('mudou_pensamento'),
    ':explicacao_pensamento' => post('explicacao_pensamento'),
    ':recomendaria' => post('recomendaria'),
    ':motivo_recomendacao' => post('motivo_recomendacao')
]);

// LOG
registrarLog(
    $pdo,
    'CREATE',
    'ficha_avaliacao_final',
    $pdo->lastInsertId(),
    "Criou ficha de avaliação final",
    $_SESSION['usuario']['id']
);

header("Location: participantes_detalhes.php?id=" . $participante_id);
exit;
