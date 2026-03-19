<?php
session_start();

require_once __DIR__ . '/../../config/conexao.php';


// Evita acesso direto
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    header("Location: login.php?erro=1");
    exit;
}

// Busca usuário (AGORA COM NIVEL)
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

    // 🔐 segurança
    session_regenerate_id(true);


    $_SESSION['usuario'] = [
        'id'    => $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email'],
        'nivel' => strtolower(trim($usuario['nivel']))
    ];

    header("Location: dashboard.php");
    exit;

} else {
    header("Location: login.php?erro=1");
    exit;
}