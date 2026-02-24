<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

if ($nome === '') {
    header("Location: turmas_lista.php");
    exit;
}

$sql = $pdo->prepare("INSERT INTO turmas (nome, descricao) VALUES (:nome, :descricao)");
$sql->bindParam(':nome', $nome);
$sql->bindParam(':descricao', $descricao);
$sql->execute();

header("Location: turmas_lista.php");
exit;
