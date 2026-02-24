<?php
session_start();
require_once __DIR__ . "/../../config/protect.php";
require_once __DIR__ .'../config/conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

// NÃO deixa excluir o próprio usuário logado
if ($id && $id != $_SESSION['usuario']['id']) {
    $sql = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $sql->execute([$id]);
}

header("Location: usuarios_lista.php?sucesso=1");
exit;

