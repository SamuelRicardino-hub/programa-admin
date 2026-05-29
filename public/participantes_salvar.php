<?php
// Se este arquivo está na raiz ou em /admin, o caminho para config é:
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/auth.php';

// ATENÇÃO: Ajustado para o nome correto do arquivo de log criado (no singular)
require_once __DIR__ . '/../config/logs.php'; 

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
    // Iniciamos uma transação para garantir consistência entre as duas tabelas
    $pdo->beginTransaction();

    if ($id) {
        // ==========================================
        // UPDATE (Edição de participante existente)
        // ==========================================
        $stmt = $pdo->prepare("
            UPDATE participantes 
            SET nome = ?, numero_processo = ?, turma_id = ?, total_passagens = ?, observacoes = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nome, $numero_processo, $turma_id, $total_passagens, $observacoes, $id]);

        // Sincroniza a tabela pivô: Remove o vínculo antigo e cria o novo
        $stmt_del = $pdo->prepare("DELETE FROM turmas_participantes WHERE participante_id = ?");
        $stmt_del->execute([$id]);

        $stmt_ins_pivo = $pdo->prepare("
            INSERT INTO turmas_participantes (turma_id, participante_id) 
            VALUES (?, ?)
        ");
        $stmt_ins_pivo->execute([$turma_id, $id]);

        // REGISTRO DE LOG DA EDIÇÃO (Salva de forma segura o que foi alterado)
        registrarLog($pdo, 'UPDATE', 'participantes', $id, "Editou o participante: " . $nome . " (Proc: " . $numero_processo . ")");

        $mensagem = "atualizado";
    } else {
        // ==========================================
        // INSERT (Novo Cadastro)
        // ==========================================
        $stmt = $pdo->prepare("
            INSERT INTO participantes (nome, numero_processo, turma_id, total_passagens, observacoes) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nome, $numero_processo, $turma_id, $total_passagens, $observacoes]);

        // 1º PASSO: Recupera IMEDIATAMENTE o ID gerado pelo banco de dados
        $novo_participante_id = $pdo->lastInsertId();

        // 2º PASSO: Insere o vínculo na tabela pivô usando o ID recém-criado
        $stmt_ins_pivo = $pdo->prepare("
            INSERT INTO turmas_participantes (turma_id, participante_id) 
            VALUES (?, ?)
        ");
        $stmt_ins_pivo->execute([$turma_id, $novo_participante_id]);

        // 3º PASSO: Com toda a estrutura salva, registra o Log de Criação com segurança
        registrarLog($pdo, 'CREATE', 'participantes', $novo_participante_id, "Cadastrou o participante: " . $nome . " (Proc: " . $numero_processo . ")");

        $mensagem = "cadastrado";
    }

    // Se tudo correu bem, confirma as alterações no banco de dados de vez
    $pdo->commit();

    // REDIRECIONAMENTO:
    header("Location: participantes_lista.php?sucesso=" . $mensagem);
    exit;
} catch (PDOException $e) {
    // Se algo falhou, desfaz as alterações para não corromper ou sujar o banco
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Exibe o erro do banco de dados para facilitar o debug
    die("Erro no banco de dados: " . $e->getMessage());
}