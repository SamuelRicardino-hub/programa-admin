<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . '/../../config/logs.php';

$id = $_GET['id'] ?? null;
$acao = $_GET['acao'] ?? null;

if (!$id || !$acao) {
    header("Location: pre_cadastros.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pre_cadastros WHERE id = ?");
$stmt->execute([$id]);
$pre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pre) {
    header("Location: pre_cadastros.php");
    exit;
}

if ($acao === "aprovar") {

    $stmt = $pdo->prepare("
        INSERT INTO participantes (nome, cpf, data_nascimento, email, telefone, turma_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $pre['nome'],
        $pre['cpf'],
        $pre['data_nascimento'],
        $pre['email'],
        $pre['telefone'],
        $turma_id
    ]);

    $stmt = $pdo->prepare("UPDATE pre_cadastros SET status = 'aprovado' WHERE id = ?");
    $stmt->execute([$id]);
    
}

if ($acao === "rejeitar") {

    $stmt = $pdo->prepare("UPDATE pre_cadastros SET status = 'rejeitado' WHERE id = ?");
    $stmt->execute([$id]);

    
}

header("Location: pre_cadastros.php");
exit;
