<?php
require_once __DIR__ . "/../config/conexao.php";

$nome = trim($_POST['nome']);
$cpf = trim($_POST['cpf']);
$email = trim($_POST['email']);
$telefone = trim($_POST['telefone']);
$data_nascimento = $_POST['data_nascimento'] ?: null;
$endereco = trim($_POST['endereco']);

if (!$nome || !$cpf || !$email) {
    die("Preencha os campos obrigatórios.");
}

// Verificar se CPF já existe como participante
$stmt = $conn->prepare("SELECT id FROM participantes WHERE cpf = ?");
$stmt->bind_param("s", $cpf);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("CPF já cadastrado.");
}

$stmt = $conn->prepare("
    INSERT INTO pre_cadastros 
    (nome, cpf, email, telefone, data_nascimento, endereco)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssss",
    $nome,
    $cpf,
    $email,
    $telefone,
    $data_nascimento,
    $endereco
);

$stmt->execute();

header("Location: index.php?sucesso=1");
exit;