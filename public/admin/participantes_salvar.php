<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();

$nome = trim($_POST['nome'] ?? '');
$numero_processo = trim($_POST['numero_processo'] ?? '');
$turma_id = $_POST['turma_id'] ?? null;
$total_passagens = $_POST['total_passagens'] ?? 0;
$observacoes = trim($_POST['observacoes'] ?? '');

// Validação
if (!$nome || !$numero_processo || !$turma_id) {
    die("Preencha os campos obrigatórios");
}

try {

    $stmt = $pdo->prepare("
        INSERT INTO participantes
        (nome, numero_processo, turma_id, total_passagens, observacoes)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $nome,
        $numero_processo,
        $turma_id,
        $total_passagens,
        $observacoes
    ]);

    header("Location: participantes_lista.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar: " . $e->getMessage());
}