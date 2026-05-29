<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

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

    // ... coloque ANTES do $stmt->execute() do DELETE de turmas_sessoes ...
    require_once __DIR__ . '/../config/logs.php';

    $stmt_info = $pdo->prepare("SELECT ts.descricao, t.nome FROM turmas_sessoes ts JOIN turmas t ON ts.turma_id = t.id WHERE ts.id = ?");
    $stmt_info->execute([$id]);
    $info = $stmt_info->fetch();

    $msg_log = $info ? "Excluiu a sessão '" . $info['descricao'] . "' da " . $info['nome'] : "Excluiu a sessão ID " . $id;

    registrarLog($pdo, 'DELETE', 'turmas_sessoes', $id, $msg_log);

    $pdo->commit();

    header("Location: sessoes_lista.php?turma_id=" . $sessao['turma_id']);
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}
