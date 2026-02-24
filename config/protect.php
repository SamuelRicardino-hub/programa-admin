<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /admin/login.php");
    exit;
}

if ($_SESSION['nivel'] !== 'admin') {
    die("Acesso restrito.");
}