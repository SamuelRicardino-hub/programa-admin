<?php
require_once __DIR__ . "/config/conexao.php";

try {

    $senhaHash = password_hash("123456", PASSWORD_DEFAULT);

    $sql = $pdo->prepare("
        INSERT INTO usuarios (nome, email, senha, nivel)
        VALUES (?, ?, ?, ?)
    ");

    $sql->execute([
        "Administrador",
        "admin@email.com",
        $senhaHash,
        "admin"
    ]);

    echo "Admin criado com sucesso!";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}