<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/logs.php';

auth();
canAny(['admin', 'atendente']);

$id = $_POST['id'] ?? null;

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$responsavel = trim($_POST['responsavel'] ?? '');
$data_inicio = $_POST['data_inicio'] ?? null;
$data_fim = $_POST['data_fim'] ?? null;
$status = $_POST['status'] ?? 'ativa';

// CAPTURA O ATENDENTE: Recebe o ID do atendente selecionado no <select> do formulário HTML
$usuario_id = !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : null;

if ($nome === '') {
    header("Location: turmas_lista.php");
    exit;
}

try {
    if ($id) {
        // ==========================================
        // UPDATE (Edição de turma existente)
        // ==========================================
        $sql = $pdo->prepare("
            UPDATE turmas SET
                nome = ?,
                descricao = ?,
                responsavel = ?,
                usuario_id = ?,
                data_inicio = ?,
                data_fim = ?,
                status = ?
            WHERE id = ?
        ");

        $sql->execute([
            $nome,
            $descricao,
            $responsavel,
            $usuario_id, // Adicionado na ordem correta
            $data_inicio,
            $data_fim,
            $status,
            $id
        ]);

        // ... após o $stmt->execute() do INSERT de turmas ...
        $nova_turma_id = $pdo->lastInsertId();
        registrarLog($pdo, 'CREATE', 'turmas', $nova_turma_id, "Criou a nova turma: " . $nome);

    } else {
        // ==========================================
        // INSERT (Cadastro de nova turma)
        // ==========================================
        $sql = $pdo->prepare("
            INSERT INTO turmas
            (nome, descricao, responsavel, usuario_id, data_inicio, data_fim, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $sql->execute([
            $nome,
            $descricao,
            $responsavel,
            $usuario_id, // Adicionado na ordem correta
            $data_inicio,
            $data_fim,
            $status
        ]);

        // Captura o ID real gerado para a nova turma
        $novaTurmaId = $pdo->lastInsertId();

        registrarLog(
            $pdo,
            'CREATE',
            'turmas',
            $novaTurmaId,
            "Criou turma: $nome"
        );
    }

    header("Location: turmas_lista.php?msg=sucesso");
    exit;
} catch (PDOException $e) {
    die("Erro ao salvar dados da turma: " . $e->getMessage());
}
