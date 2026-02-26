<?php
require_once __DIR__ . "/config/conexao.php";

$senhaHash = password_hash("123456", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO usuarios (nome, email, senha)
    VALUES (?, ?, ?)
");

$stmt->execute([
    "Administrador",
    "admin@email.com",
    $senhaHash
]);

echo "Usuário admin criado com sucesso!";