<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

// Evita acesso direto
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: participantes_lista.php");
    exit;
}

// Recebe dados
$nome      = trim($_POST['nome'] ?? '');
$telefone  = trim($_POST['telefone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$turma_id  = $_POST['turma_id'] ?? null;

// Validação básica
if ($nome === '' || $email === '' || !$turma_id) {
    header("Location: participantes_form.php?erro=1");
    exit;
}

// Verifica CPF duplicado
$check = $pdo->prepare("SELECT id FROM participantes WHERE email = :email LIMIT 1");
$check->bindParam(':email', $email);
$check->execute();

if ($check->fetch()) {
    header("Location: participantes_form.php?erro=cpf");
    exit;
}

// Insere participante
$sql = $pdo->prepare("
    INSERT INTO participantes (nome, telefone, email, turma_id)
    VALUES (:nome, :telefone, :email, :turma_id)
");

$sql->execute([
    ':nome'     => $nome,
    ':telefone' => $telefone,
    ':email'    => $email,
    ':turma_id' => $turma_id
]);

// Redireciona para lista
header("Location: participantes_lista.php");
exit;
