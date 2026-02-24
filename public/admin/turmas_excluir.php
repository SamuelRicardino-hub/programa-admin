<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$id = $_GET['id'] ?? null;

// Valida ID
if (!$id) {
    header("Location: turmas_lista.php");
    exit;
}

/*
 OPCIONAL (RECOMENDADO):
 Verifica se a turma tem participantes
*/
$sql = $pdo->prepare("SELECT COUNT(*) FROM participantes WHERE turma_id = :id");
$sql->bindParam(':id', $id);
$sql->execute();

$total = $sql->fetchColumn();

if ($total > 0) {
    // Impede exclusão se houver participantes
    header("Location: turmas_lista.php?erro=turma_em_uso");
    exit;
}

// Exclui a turma
$sql = $pdo->prepare("DELETE FROM turmas WHERE id = :id");
$sql->bindParam(':id', $id);
$sql->execute();

header("Location: turmas_lista.php?excluido=1");
exit;
