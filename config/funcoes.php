<?php

function calcularIdade($dataNascimento)
{
    $hoje = new DateTime();
    $nasc = new DateTime($dataNascimento);
    return $hoje->diff($nasc)->y;
}
function registrarLog($pdo, $usuario_id, $acao)
{
    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, acao) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $acao]);
}
function usuarioLogado()
{
    return $_SESSION['usuario'] ?? null;
}

function isAdmin()
{
    return isset($_SESSION['usuario']) && $_SESSION['usuario']['nivel'] === 'admin';
}
