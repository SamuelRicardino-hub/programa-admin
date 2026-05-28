<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';

auth();
canAny(['admin', 'atendente']);

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: turmas_lista.php");
    exit;
}

// Recebe dados
$id           = $_POST['id'] ?? null;
$nome         = trim($_POST['nome'] ?? '');
$descricao    = trim($_POST['descricao'] ?? '');
$responsavel  = trim($_POST['responsavel'] ?? '');
$data_inicio  = $_POST['data_inicio'] ?? null;
$data_fim     = $_POST['data_fim'] ?? null;
$status       = $_POST['status'] ?? 'ativa';

// Validação básica
if (!$id || $nome === '') {
    header("Location: turmas_lista.php");
    exit;
}

try {

    // 🔍 Dados antigos
    $stmtOld = $pdo->prepare("SELECT nome FROM turmas WHERE id = ?");
    $stmtOld->execute([$id]);
    $antigo = $stmtOld->fetch(PDO::FETCH_ASSOC);

    // 🔄 UPDATE COMPLETO
    $sql = $pdo->prepare("
        UPDATE turmas 
        SET nome = :nome,
            descricao = :descricao,
            responsavel = :responsavel,
            data_inicio = :data_inicio,
            data_fim = :data_fim,
            status = :status
        WHERE id = :id
    ");

    $sql->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':responsavel' => $responsavel,
        ':data_inicio' => $data_inicio,
        ':data_fim' => $data_fim,
        ':status' => $status,
        ':id' => $id
    ]);

    // ... após o $stmt->execute() do UPDATE de turmas ...
    registrarLog($pdo, 'UPDATE', 'turmas', $id, "Atualizou os dados da turma: " . $nome);
    
} catch (PDOException $e) {
    die("Erro ao atualizar turma");
}

// Redireciona
header("Location: turmas_lista.php");
exit;
