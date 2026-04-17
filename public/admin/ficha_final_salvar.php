<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido");
}

function tratar($v) {
    return (isset($v) && trim($v) !== '') ? trim($v) : null;
}

$participante_id = $_POST['participante_id'] ?? null;

if (!$participante_id) {
    die("Participante não informado");
}

// TRATAR CHECKBOX (temas)
$temas = isset($_POST['temas_importantes']) 
    ? implode(', ', $_POST['temas_importantes']) 
    : null;

// DADOS
$dados = [
    tratar($_POST['sentimento_denuncia']),
    tratar($_POST['acha_justa']),
    tratar($_POST['motivo_denuncia']),
    tratar($_POST['dificuldade_participar']),
    tratar($_POST['motivo_dificuldade']),
    tratar($_POST['avaliacao_participacao']),
    tratar($_POST['pontos_positivos']),
    tratar($_POST['pontos_negativos']),
    $temas,
    tratar($_POST['houve_mudanca']),
    tratar($_POST['descricao_mudanca']),
    tratar($_POST['gostou_grupo']),
    tratar($_POST['sentimento_inicio']),
    tratar($_POST['recomendaria']),
    tratar($_POST['motivo_recomendacao']),
    tratar($_POST['sugestoes']),
    $participante_id
];

// VERIFICA SE JÁ EXISTE
$stmt = $pdo->prepare("SELECT id FROM ficha_avaliacao_final WHERE participante_id = ?");
$stmt->execute([$participante_id]);

if ($stmt->fetch()) {

    // UPDATE
    $sql = "UPDATE ficha_avaliacao_final SET
        sentimento_denuncia = ?,
        acha_justa = ?,
        motivo_denuncia = ?,
        dificuldade_participar = ?,
        motivo_dificuldade = ?,
        avaliacao_participacao = ?,
        pontos_positivos = ?,
        pontos_negativos = ?,
        temas_importantes = ?,
        houve_mudanca = ?,
        descricao_mudanca = ?,
        gostou_grupo = ?,
        sentimento_inicio = ?,
        recomendaria = ?,
        motivo_recomendacao = ?,
        sugestoes = ?
        WHERE participante_id = ?";

} else {

    // INSERT
    $sql = "INSERT INTO ficha_avaliacao_final (
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
        gostou_grupo,
        sentimento_inicio,
        recomendaria,
        motivo_recomendacao,
        sugestoes,
        participante_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

}

$stmt = $pdo->prepare($sql);
$stmt->execute($dados);

// REDIRECIONA
header("Location: participantes_detalhes.php?id=" . $participante_id);
exit;