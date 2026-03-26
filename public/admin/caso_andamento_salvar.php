<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

$caso_id = $_POST['caso_id'] ?? null;
$descricao = $_POST['descricao'] ?? '';

if (!$caso_id || empty($descricao)) {
    die("Dados inválidos");
}

// 👤 usuário logado
$usuario_id = $_SESSION['usuario']['id'] ?? null;

// 💾 salvar
$stmt = $pdo->prepare("
    INSERT INTO casos_andamento (caso_id, descricao, usuario_id)
    VALUES (?, ?, ?)
");

$stmt->execute([$caso_id, $descricao, $usuario_id]);

// 🔁 voltar
header("Location: caso_andamento.php?caso_id=" . $caso_id);
exit;