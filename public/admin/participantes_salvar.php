<?php
require_once __DIR__ .'../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
canAny(['admin', 'atendente']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: participantes_lista.php");
    exit;
}

$nome      = trim($_POST['nome'] ?? '');
$telefone  = trim($_POST['telefone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$total_passagens = $_POST['total_passagens'] ?? 0;
$endereco = trim($_POST['endereco']);
$bairro = trim($_POST['bairro']);
$turma_id  = $_POST['turma_id'] ?? null;

if ($nome === '' || $email === '' || !$turma_id) {
    header("Location: participantes_form.php?erro=1");
    exit;
}

$check = $pdo->prepare("SELECT id FROM participantes WHERE email = :email LIMIT 1");
$check->bindParam(':email', $email);
$check->execute();

if ($check->fetch()) {
    header("Location: participantes_form.php?erro=cpf");
    exit;
}

$insert = $pdo->prepare("
            INSERT INTO participantes
            (nome, cpf, data_nascimento, telefone, email, endereco, bairro, turma_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $insert->execute([
            $pre['nome'],
            $pre['cpf'],
            $pre['data_nascimento'],
            $pre['telefone'],
            $pre['email'],
            $pre['endereco'],
            $pre['bairro'],
            $turma_id
        ]);
header("Location: participantes_lista.php");
exit;
