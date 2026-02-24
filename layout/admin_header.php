<?php
require_once __DIR__ . "/../config/protect.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="/admin.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>

    <a href="/admin/dashboard.php">Dashboard</a>
    <a href="/admin/pre_cadastros_lista.php">Pré-Cadastros</a>
    <a href="/admin/participantes_lista.php">Participantes</a>
    <a href="/admin/turmas_lista.php">Turmas</a>
    <a href="/admin/usuarios_lista.php">Usuários</a>

    <hr>

    <a href="/admin/logout.php" class="logout">Sair</a>
</div>

<div class="content">