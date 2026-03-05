<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$id    = $_POST['id'] ?? null;
$nome  = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (!$id || $nome === '' || $email === '') {
    header("Location: usuarios_lista.php");
    exit;
}

if ($senha !== '') {
   
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = $pdo->prepare("
        UPDATE usuarios
        SET nome = :nome, email = :email, senha = :senha
        WHERE id = :id
    ");
    $sql->bindParam(':senha', $hash);
} else {
    // Atualiza sem alterar senha
    $sql = $pdo->prepare("
        UPDATE usuarios
        SET nome = :nome, email = :email
        WHERE id = :id
    ");
}

$sql->bindParam(':nome', $nome);
$sql->bindParam(':email', $email);
$sql->bindParam(':id', $id);
$sql->execute();

header("Location: usuarios_lista.php");
exit;
