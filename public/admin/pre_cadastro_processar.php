<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ . "/../../config/conexao.php";

$id = $_GET['id'] ?? null;
$acao = $_GET['acao'] ?? null;

if (!$id || !$acao) {
    header("Location: pre_cadastros.php");
    exit;
}

// Buscar pré-cadastro
$stmt = $pdo->prepare("SELECT * FROM pre_cadastros WHERE id = ?");
$stmt->execute([$id]);
$pre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pre) {
    header("Location: pre_cadastros.php");
    exit;
}

if ($acao === "aprovar") {

    // Inserir em participantes
    $stmt = $pdo->prepare("
        INSERT INTO participantes (nome, cpf, email, telefone)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $pre['nome'],
        $pre['cpf'],
        $pre['email'],
        $pre['telefone']
    ]);

    // Atualizar status
    $stmt = $pdo->prepare("UPDATE pre_cadastros SET status = 'aprovado' WHERE id = ?");
    $stmt->execute([$id]);

}

if ($acao === "rejeitar") {

    $stmt = $pdo->prepare("UPDATE pre_cadastros SET status = 'rejeitado' WHERE id = ?");
    $stmt->execute([$id]);

}

header("Location: pre_cadastros.php");
exit;