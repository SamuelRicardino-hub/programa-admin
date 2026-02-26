<?php
require_once __DIR__ . "/../config/conexao.php";

$nome = trim($_POST['nome']);
$cpf = trim($_POST['cpf']);
$idade = intval($_POST['idade']);
$naturalidade = trim($_POST['naturalidade']);
$telefone = trim($_POST['telefone']);
$email = trim($_POST['email']);

try {

    // Verifica CPF duplicado
    $stmt = $pdo->prepare("SELECT id FROM pre_cadastros WHERE cpf = ?");
    $stmt->execute([$cpf]);

    if ($stmt->rowCount() > 0) {
        header("Location: pre_cadastro.php?erro=1");
        exit;
    }

    // Inserir
    $stmt = $pdo->prepare("
        INSERT INTO pre_cadastros
        (nome, cpf, idade, naturalidade, telefone, email)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $nome,
        $cpf,
        $idade,
        $naturalidade,
        $telefone,
        $email
    ]);

    header("Location: index.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar: " . $e->getMessage());
}