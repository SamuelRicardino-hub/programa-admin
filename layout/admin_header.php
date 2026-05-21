<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Projeto S.E.R - Painel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --ser-orange: #F29433;
            --ser-blue: #3684C4;
            --ser-wine: #A61C62;
            --ser-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--ser-light);
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #212529; /* Dark neutro */
            border-top: 5px solid var(--ser-orange); /* Detalhe com a cor da imagem */
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            margin-right: 12px;
            color: var(--ser-blue); /* Ícones no azul da imagem */
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: var(--ser-orange); /* Texto no laranja ao passar o mouse */
        }

        .sidebar .nav-link.active {
            background-color: var(--ser-blue);
            color: white;
        }
        
        .sidebar .nav-link.active i {
            color: white;
        }

        .brand-area {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
        }

        .sidebar .text-danger {
            color: #ff6b6b !important;
            margin-top: 20px;
        }
        
        .sidebar .text-danger:hover {
            background-color: rgba(166, 28, 98, 0.1); /* Sutil toque de vinho no hover do sair */
            color: var(--ser-wine) !important;
        }
    </style>
</head>

<body>

    <div class="d-flex">

        <div class="sidebar text-white shadow">
            <div class="brand-area">
                <h5 class="mb-0 fw-bold" style="letter-spacing: 1px;">
                    <span style="color: var(--ser-orange);">PROJETO</span> S.E.R
                </h5>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/public/dashboard.php" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Página Inicial
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/public/turmas_lista.php" class="nav-link">
                        <i class="bi bi-mortarboard"></i> Turmas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/public/participantes_lista.php" class="nav-link">
                        <i class="bi bi-people"></i> Participantes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/public/usuarios_lista.php" class="nav-link">
                        <i class="bi bi-person-gear"></i> Usuários
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/public/busca.php" class="nav-link">
                        <i class="bi bi-search"></i> Buscar
                    </a>
                </li>

                <li class="nav-item mt-auto">
                    <a href="/public/logout.php" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4 bg-light">