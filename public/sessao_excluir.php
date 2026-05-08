<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';

auth();
canAny(['admin']);

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Sessão não informada");
}

// Buscar sessão
$stmt = $pdo->prepare("SELECT * FROM turmas_sessoes WHERE id = ?");
$stmt->execute([$id]);
$sessao = $stmt->fetch();

if (!$sessao) {
    die("Sessão não encontrada");
}

try {

    $pdo->beginTransaction();

    // 🔴 EXCLUIR PRESENÇAS
    $stmt = $pdo->prepare("DELETE FROM presencas WHERE sessao_id = ?");
    $stmt->execute([$id]);

    // 🔴 EXCLUIR SESSÃO
    $stmt = $pdo->prepare("DELETE FROM turmas_sessoes WHERE id = ?");
    $stmt->execute([$id]);

    // LOG
    registrarLog(
        $pdo,
        'DELETE',
        'turmas_sessoes',
        $id,
        "Excluiu sessão ID $id",
        $_SESSION['usuario']['id']
    );

    $pdo->commit();

    header("Location: sessoes_lista.php?turma_id=" . $sessao['turma_id']);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}