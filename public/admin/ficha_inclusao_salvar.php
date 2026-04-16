<?php
require_once __DIR__ . '/../../config/conexao.php';

$dados = $_POST;

$stmt = $pdo->prepare("
INSERT INTO ficha_inclusao (
    participante_id, cor, situacao_civil, religiao, escolaridade,
    renda_familiar, profissao, problemas_saude, uso_alcool,
    drogas_utilizadas, violencia_praticada, violencia_sofrida,
    historico_familiar, situacao_juridica, expectativa_grupo
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $dados['participante_id'],
    $dados['cor'] ?? null,
    $dados['situacao_civil'] ?? null,
    $dados['religiao'] ?? null,
    $dados['escolaridade'] ?? null,
    $dados['renda_familiar'] ?? null,
    $dados['profissao'] ?? null,
    $dados['problemas_saude'] ?? null,
    $dados['uso_alcool'] ?? null,
    $dados['drogas_utilizadas'] ?? null,
    $dados['violencia_praticada'] ?? null,
    $dados['violencia_sofrida'] ?? null,
    $dados['historico_familiar'] ?? null,
    $dados['situacao_juridica'] ?? null,
    $dados['expectativa_grupo'] ?? null
]);

header("Location: participante_fichas.php?id=" . $dados['participante_id']);
exit;