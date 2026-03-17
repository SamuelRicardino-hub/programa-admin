<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once '/../../config/auth.php';

auth();
can('admin');

$id = intval($_GET['id']);

$stmt = $pdo->prepare("
    UPDATE pre_cadastros
    SET status = 'aprovado'
    WHERE id = ?
");

$stmt->execute([$id]);

header("Location: pre_cadastros.php");
exit;