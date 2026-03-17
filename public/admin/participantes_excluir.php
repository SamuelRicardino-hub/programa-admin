<?php
require_once __DIR__ .'/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();
can('admin');

$id = $_GET['id'] ?? null;
$turma_id = $_GET['turma_id'] ?? null;

if (!$id) {
    header("Location: participantes_lista.php");
    exit;
}

$sql = $pdo->prepare("DELETE FROM participantes WHERE id = :id");
$sql->bindParam(':id', $id);
$sql->execute();

if ($turma_id) {
    header("Location: participantes_lista.php?turma_id=" . $turma_id);
} else {
    header("Location: participantes_lista.php");
}
exit;
