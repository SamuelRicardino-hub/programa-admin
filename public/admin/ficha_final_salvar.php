<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

$dados = $_POST;

$temas = isset($dados['temas_importantes']) 
    ? implode(', ', $dados['temas_importantes']) 
    : null;

$stmt = $pdo->prepare("
INSERT INTO ficha_avaliacao_final (
    participante_id,
    sentimento_denuncia,
    acha_justa,
    motivo_denuncia,
    dificuldade_participar,
    motivo_dificuldade,
    avaliacao_participacao,
    temas_importantes,
    houve_mudanca,
    descricao_mudanca,
    gostou_grupo,
    como_saiu,
    recomendaria,
    motivo_recomendacao,
    sugestoes
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $dados['participante_id'],
    $dados['sentimento_denuncia'] ?? null,
    $dados['acha_justa'] ?? null,
    $dados['motivo_denuncia'] ?? null,
    $dados['dificuldade_participar'] ?? null,
    $dados['motivo_dificuldade'] ?? null,
    $dados['avaliacao_participacao'] ?? null,
    $temas,
    $dados['houve_mudanca'] ?? null,
    $dados['descricao_mudanca'] ?? null,
    $dados['gostou_grupo'] ?? null,
    $dados['como_saiu'] ?? null,
    $dados['recomendaria'] ?? null,
    $dados['motivo_recomendacao'] ?? null,
    $dados['sugestoes'] ?? null
]);

header("Location: participante_fichas.php?id=" . $dados['participante_id']);
exit;