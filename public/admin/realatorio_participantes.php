<?php
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

$turma_id = $_GET['turma_id'] ?? null;

$sql = "
    SELECT p.nome, p.email, t.nome AS turma
    FROM participantes p
    JOIN turmas t ON t.id = p.turma_id
";

if ($turma_id) {
    $sql .= " WHERE p.turma_id = :turma_id";
}

$sql .= " ORDER BY t.nome, p.nome";

$stmt = $pdo->prepare($sql);

if ($turma_id) {
    $stmt->bindParam(':turma_id', $turma_id);
}

$stmt->execute();
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
