<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$email = $_POST['email'] ?? null;
$telefone = $_POST['telefone'] ?? null;
$turma_id = $_POST['turma_id'] ?? null;

if (!$id || !$nome || !$email || !$turma_id) {
    die("Dados incompletos");
}


$sql = $pdo->prepare("
    UPDATE participantes
    SET nome = :nome,
        email = :email,
        telefone = :telefone,
        turma_id = :turma_id
    WHERE id = :id
");

$sql->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':telefone' => $telefone,
    ':turma_id' => $turma_id,
    ':id' => $id
]);


header("Location: participantes_lista.php");
exit;
