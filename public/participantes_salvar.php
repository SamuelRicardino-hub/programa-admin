<?php
// Se este arquivo está na raiz ou em /admin, o caminho para config é:
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

auth();

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$numero_processo = trim($_POST['numero_processo'] ?? '');
$total_passagens = $_POST['total_passagens'] ?? 0;
$turma_id = $_POST['turma_id'] ?? null; 
$observacoes = trim($_POST['observacoes'] ?? '');

// Validação: Garante que os campos essenciais não cheguem vazios
if (!$nome || !$numero_processo || !$turma_id) {
    die("Erro: Nome, Número do Processo e Turma são obrigatórios.");
}

try {
    if ($id) {
        // ==========================================
        // UPDATE (Edição de participante existente)
        // ==========================================
        $stmt = $pdo->prepare("
            UPDATE participantes 
            SET nome = ?, numero_processo = ?, turma_id = ?,total_passagens = ?, observacoes = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nome, $numero_processo, $turma_id, $total_passagens, $observacoes, $id]);
        $mensagem = "atualizado";
    } else {
        // ==========================================
        // INSERT (Novo Cadastro)
        // ==========================================
        $stmt = $pdo->prepare("
            INSERT INTO participantes (nome, numero_processo, turma_id, totalpassagens, observacoes) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nome, $numero_processo, $turma_id, $total_passagens, $observacoes]);
        $mensagem = "cadastrado";
    }

    // REDIRECIONAMENTO:
    // Se a lista de participantes estiver na mesma pasta que este arquivo:
    header("Location: participantes_lista.php?sucesso=" . $mensagem);
    exit;

} catch (PDOException $e) {
    // Exibe o erro do banco de dados para facilitar o debug
    die("Erro no banco de dados: " . $e->getMessage());
}