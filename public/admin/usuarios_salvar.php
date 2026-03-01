<?php
session_start();
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'/../../config/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$id    = $_POST['id'] ?? null;
$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'] ?? '';

if ($id) {
    // EDITAR
    if ($senha) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = $pdo->prepare(
            "UPDATE usuarios SET nome=?, email=?, senha=? WHERE id=?"
        );
        $sql->execute([$nome, $email, $hash, $id]);
    } else {
        $sql = $pdo->prepare(
            "UPDATE usuarios SET nome=?, email=? WHERE id=?"
        );
        $sql->execute([$nome, $email, $id]);
    }
} else {
    // NOVO
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = $pdo->prepare(
        "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)"
    );
    $sql->execute([$nome, $email, $hash]);
}

header("Location: usuarios_lista.php");
exit;
