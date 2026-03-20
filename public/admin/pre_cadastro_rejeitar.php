<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/auth.php";
require_once __DIR__ . '/../../config/logs.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    UPDATE pre_cadastros 
    SET status = 'rejeitado'
    WHERE id = ?
");

registrarLog(
    $pdo,
    'REJEICAO',
    'pre_cadastros',
    $id,
    "Reprovou pré-cadastro ID $id"
);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: pre_cadastros_lista.php");
exit;
