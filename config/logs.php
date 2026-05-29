<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function registrarLog($pdo, $tipo, $entidade, $entidade_id, $mensagem_acao) {
    try {
        // Pega o ID do Administrador ou Atendente logado na sessão
        $usuario_id = $_SESSION['usuario_id'] ?? $_SESSION['usuario']['id'] ?? null;

        if (!$usuario_id) return false; // Evita gravar logs sem usuário

        $sql = $pdo->prepare("
            INSERT INTO logs (usuario_id, acao, tipo, entidade, entidade_id, data)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        return $sql->execute([
            $usuario_id,
            $mensagem_acao,       // Texto descritivo legível
            strtoupper($tipo),    // 'CREATE', 'UPDATE', 'DELETE'
            strtolower($entidade),// Nome da tabela afetada
            $entidade_id          // ID da linha alterada
        ]);

    } catch (Exception $e) {
        error_log("Falha ao registrar log no sistema: " . $e->getMessage());
        return false;
    }
}