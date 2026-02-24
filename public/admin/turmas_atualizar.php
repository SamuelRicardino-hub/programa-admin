<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: turmas_lista.php");
    exit;
}

// Recebe dados
$id        = $_POST['id'] ?? null;
$nome      = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

// Validação básica
if (!$id || $nome === '') {
    header("Location: turmas_lista.php");
    exit;
}

// Atualiza no banco
$sql = $pdo->prepare("
    UPDATE turmas 
    SET nome = :nome, descricao = :descricao
    WHERE id = :id
");

$sql->bindParam(':nome', $nome);
$sql->bindParam(':descricao', $descricao);
$sql->bindParam(':id', $id);

$sql->execute();

// Volta para a lista
header("Location: turmas_lista.php");
exit;
