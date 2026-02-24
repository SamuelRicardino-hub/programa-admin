<?php
require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../config/protect.php";

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    UPDATE pre_cadastros 
    SET status = 'rejeitado'
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: pre_cadastros_lista.php");
exit;