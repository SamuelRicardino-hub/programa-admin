<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Assistência Social</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .navbar-custom {
            background-color: #1e3a8a;
        }

        .hero {
            padding: 80px 0;
            text-align: center;
        }

        .footer-custom {
            background-color: #1e3a8a;
            color: white;
            padding: 20px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="/programa-admin/public">
            Assistência Social
        </a>

        <div class="ms-auto">
            <a href="/programa-admin/public/admin/login.php"
               class="btn btn-outline-light btn-sm">
               Área Administrativa
            </a>
        </div>
    </div>
</nav>

<div class="container">