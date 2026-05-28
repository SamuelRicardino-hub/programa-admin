<?php

$host = "localhost";
$db   = "programa_admin";
$user = "projetoser";
$pass = "Lb977U0hJvyzHi1nZp20";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
