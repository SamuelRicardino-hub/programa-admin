<?php
session_start();

require_once __DIR__ . '/../config/conexao.php';

// Evita acesso direto via URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Verifica campos vazios
if ($email === '' || $senha === '') {
    header("Location: ../index.php?erro=1");
    exit;
}

try {
    $sql = $pdo->prepare("
        SELECT id, nome, email, senha, nivel 
        FROM usuarios 
        WHERE email = :email 
        LIMIT 1
    ");

    $sql->bindParam(':email', $email);
    $sql->execute();
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        
        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id'    => $usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'nivel' => strtolower(trim($usuario['nivel']))
        ];

        // AGORA: Se o dashboard está na mesma pasta raiz que o index.php
        // O caminho sai de /config/ e vai para a raiz
        header("Location: dashboard.php"); 
        exit;

    } else {
        header("Location: ../index.php?erro=1");
        exit;
    }

} catch (PDOException $e) {
    die("Erro no sistema: " . $e->getMessage());
}