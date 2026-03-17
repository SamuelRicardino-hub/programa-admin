<?php
session_start();
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ .'/../../config/auth.php';

auth();
can('admin');


$id    = $_POST['id'] ?? null;
$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = $_POST['senha'] ?? '';
$nivel = $_POST['nivel'];

if ($id) {

    if ($senha) {

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = $pdo->prepare(
            "UPDATE usuarios SET nome=?, email=?, senha=?, nivel=? WHERE id=?"
        );
        $sql->execute([$nome, $email, $hash, $nivel, $id]);

    } else {

        $sql = $pdo->prepare(
            "UPDATE usuarios SET nome=?, email=?, nivel=? WHERE id=?"
        );
        $sql->execute([$nome, $email, $nivel, $id]);

    }

} else {

    if (!$senha) {
        die("Senha obrigatória para novo usuário.");
    }

    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = $pdo->prepare(
        "INSERT INTO usuarios (nome, email, senha, nivel) VALUES (?, ?, ?, ?)"
    );
    $sql->execute([$nome, $email, $hash, $nivel]);
}

header("Location: usuarios_lista.php");
exit;