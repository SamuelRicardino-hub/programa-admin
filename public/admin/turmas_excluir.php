<?php
require_once __DIR__ .'/../../config/conexao.php';
require_once __DIR__ .'/../../config/auth.php';
require_once __DIR__ . '/../../config/log.php';

auth();
can('admin');

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
$stmtOld = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
$stmtOld->execute([$id]);
$turma = $stmtOld->fetch(PDO::FETCH_ASSOC);

$sql = $pdo->prepare("DELETE FROM turmas WHERE id = :id");
$sql->bindParam(':id', $id);
$sql->execute();

registrarLog(
    $pdo,
    'DELETE',
    'turmas',
    $id,
    "Excluiu turma: {$turma['nome']} (ID $id)"
);

header("Location: turmas_lista.php?excluido=1");
exit;
