<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function auth() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: /programa-admin/public/admin/login.php");
        exit;
    }
}

function can($nivel) {
    auth();

    if ($_SESSION['usuario']['nivel'] !== $nivel) {
        http_response_code(403);
        die("Acesso negado.");
    }
}

function canAny($niveis = []) {
    auth();

    if (!in_array($_SESSION['usuario']['nivel'], $niveis)) {
        http_response_code(403);
        die("Acesso negado.");
    }
}