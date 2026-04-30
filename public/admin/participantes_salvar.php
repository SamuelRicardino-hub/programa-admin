<?php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../config/auth.php';

auth();

// Recebe os dados do formulário
$id = $_POST['id'] ?? null; // ID virá apenas na edição
$nome = trim($_POST['nome'] ?? '');
$numero_processo = trim($_POST['numero_processo'] ?? '');
$observacoes = trim($_POST['observacoes'] ?? '');

// Validação básica
if (!$nome || !$numero_processo) {
    die("Erro: Nome e Número do Processo são obrigatórios.");
}

try {
    if ($id) {
        // ==========================================
        // LÓGICA DE EDIÇÃO (UPDATE)
        // ==========================================
        $stmt = $pdo->prepare("
            UPDATE participantes 
            SET nome = ?, numero_processo = ?, observacoes = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nome, $numero_processo, $observacoes, $id]);
        $mensagem = "atualizado";
    } else {
        // ==========================================
        // LÓGICA DE NOVO CADASTRO (INSERT)
        // ==========================================
        $stmt = $pdo->prepare("
            INSERT INTO participantes (nome, numero_processo, observacoes) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nome, $numero_processo, $observacoes]);
        $mensagem = "cadastrado";
    }

    // Redireciona de volta para a lista com uma mensagem de sucesso
    header("Location: participantes_lista.php?sucesso=" . $mensagem);
    exit;

} catch (PDOException $e) {
    // Em produção, você pode trocar o getMessage() por algo mais genérico
    die("Erro no banco de dados: " . $e->getMessage());
}