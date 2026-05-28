<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Registra uma ação de auditoria no banco de dados.
 * * @param PDO $pdo Conexão com o banco
 * @param string $acao Tipo de operação (CREATE, UPDATE, DELETE, etc.)
 * @param string $tabela Tabela afetada
 * @param int|null $registro_id ID do registro afetado
 * @param string $descricao Texto descritivo da ação
 */
function registrarLog($pdo, $acao, $tabela, $registro_id, $descricao) {
    try {
        // Captura os dados de quem está logado na sessão
        $usuario_id   = $_SESSION['usuario_id'] ?? $_SESSION['usuario']['id'] ?? null;
        $usuario_nome = $_SESSION['usuario_nome'] ?? $_SESSION['usuario']['nome'] ?? 'Sistema/Anônimo';

        $sql = $pdo->prepare("
            INSERT INTO logs (usuario_id, usuario_nome, acao, tabela, registro_id, descricao)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $sql->execute([
            $usuario_id,
            $usuario_nome,
            strtoupper($acao),
            strtolower($tabela),
            $registro_id,
            $descricao
        ]);
        
    } catch (PDOException $e) {
        // Grava em um arquivo de texto local caso o banco de logs falhe, evitando travar o sistema principal
        error_log("Erro ao salvar log no banco: " . $e->getMessage());
    }
}