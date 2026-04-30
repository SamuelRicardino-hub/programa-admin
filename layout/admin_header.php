<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="d-flex">

        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
            <h5 class="mb-4">Admin</h5>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/programa-admin/public/admin/dashboard.php" class="nav-link text-white">Página Inicial</a>
                </li>
                <li class="nav-item">
                    <a href="/programa-admin/public/admin/turmas_lista.php" class="nav-link text-white">Turmas</a>
                </li>
                <li class="nav-item">
                    <a href="/programa-admin/public/admin/participantes_lista.php" class="nav-link text-white">Participantes</a>
                </li>
                <li class="nav-item">
                    <a href="/programa-admin/public/admin/usuarios_lista.php" class="nav-link text-white">Usuários</a>
                </li>
                <li class="nav-item">
                    <a href="/programa-admin/public/admin/busca.php" class="nav-link text-white">Buscar</a>
                </li>
            
                </li>
                <li class="nav-item mt-4">
                    <a href="/programa-admin/public/admin/logout.php" class="nav-link text-danger">Sair</a>
                </li>
            </ul>
        </div>

        <!-- Conteúdo -->
        <div class="flex-grow-1 p-4 bg-light">