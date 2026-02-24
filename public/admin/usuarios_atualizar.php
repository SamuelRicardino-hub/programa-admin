<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$id    = $_POST['id'] ?? null;
$nome  = trim($_POST['nome'] ?? '');
$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';

if (!$id || $nome === '' || $login === '') {
    header("Location: usuarios_lista.php");
    exit;
}

if ($senha !== '') {
    // Atualiza com nova senha
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = $pdo->prepare("
        UPDATE usuarios
        SET nome = :nome, login = :login, senha = :senha
        WHERE id = :id
    ");
    $sql->bindParam(':senha', $hash);
} else {
    // Atualiza sem alterar senha
    $sql = $pdo->prepare("
        UPDATE usuarios
        SET nome = :nome, login = :login
        WHERE id = :id
    ");
}

$sql->bindParam(':nome', $nome);
$sql->bindParam(':login', $login);
$sql->bindParam(':id', $id);
$sql->execute();

header("Location: usuarios_lista.php");
exit;
