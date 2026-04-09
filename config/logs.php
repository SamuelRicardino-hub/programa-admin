<?php

function registrarLog($pdo, $tipo, $entidade, $entidade_id = null, $acao = null, $dados = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $usuario_id = $_SESSION['usuario']['id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;

    // Converte dados extras para JSON
    if (is_array($dados)) {
        $dados = json_encode($dados, JSON_UNESCAPED_UNICODE);
    }

    try {
        $sql = $pdo->prepare("
            INSERT INTO logs 
            (usuario_id, tipo, entidade, entidade_id, acao, dados, ip)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $sql->execute([
            $usuario_id,
            $tipo,
            $entidade,
            $entidade_id,
            $acao,
            $dados,
            $ip
        ]);

    } catch (Exception $e) {
        error_log("Erro ao registrar log: " . $e->getMessage());
    }
}