<?php
session_start();
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$id    = $_POST['id'] ?? null;
$nome  = $_POST['nome'];
$login = $_POST['login'];
$senha = $_POST['senha'] ?? '';

if ($id) {
    // EDITAR
    if ($senha) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = $pdo->prepare(
            "UPDATE usuarios SET nome=?, login=?, senha=? WHERE id=?"
        );
        $sql->execute([$nome, $login, $hash, $id]);
    } else {
        $sql = $pdo->prepare(
            "UPDATE usuarios SET nome=?, login=? WHERE id=?"
        );
        $sql->execute([$nome, $login, $id]);
    }
} else {
    // NOVO
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = $pdo->prepare(
        "INSERT INTO usuarios (nome, login, senha) VALUES (?, ?, ?)"
    );
    $sql->execute([$nome, $login, $hash]);
}

header("Location: usuarios_lista.php");
exit;
