<?php
require_once __DIR__ . '/../../config/conexao.php';

$dados = $_POST;

try {

    $sql = $pdo->prepare("
        INSERT INTO ficha_inclusao (
            participante_id,
            numero_caso,
            numero_processo,
            nome_completo,
            parentesco,
            idade,
            naturalidade,
            cor,
            relacionamento_atual,
            relacionamento_detalhe,
            religiao,
            escolaridade,
            renda_familiar,
            trabalho,
            profissao,
            condicao_moradia
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $sql->execute([
        $dados['participante_id'],
        $dados['numero_caso'],
        $dados['numero_processo'],
        $dados['nome_completo'],
        $dados['parentesco'],
        $dados['idade'],
        $dados['naturalidade'],
        $dados['cor'],
        $dados['relacionamento_atual'],
        $dados['relacionamento_detalhe'],
        $dados['religiao'],
        $dados['escolaridade'],
        $dados['renda_familiar'],
        $dados['trabalho'],
        $dados['profissao'],
        $dados['condicao_moradia']
    ]);

    header("Location: participantes_detalhes.php?id=" . $dados['participante_id']);
    exit;

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}